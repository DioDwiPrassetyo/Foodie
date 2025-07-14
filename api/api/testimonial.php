<?php
include 'connect.php';
session_start();

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Preflight for CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$isBrowser = $method === "GET" && !isset($_GET["id_users"]) && !isset($_SERVER['HTTP_X_REQUESTED_WITH']);

// === FORM HTML (buka dari browser manual) ===
if ($isBrowser) {
    // Ambil semua testimonial untuk ditampilkan
    $result = $conn->query("SELECT * FROM testimonials ORDER BY id DESC");
    $testimonials = $result->fetch_all(MYSQLI_ASSOC);

    // Header HTML
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Testimonial</title>
        <style>
            body {
                font-family: Arial;
                background-color: #f8f8f8;
                margin: 0;
                padding: 40px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
            }
            form {
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                margin-bottom: 30px;
            }
            input, textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }
            button {
                background-color: #28a745;
                color: white;
                padding: 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                background: white;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ccc;
                text-align: left;
            }
            th {
                background-color: #007bff;
                color: white;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Tambah Testimonial</h2>
        <form method="POST" action="testimonial.php">
            <input type="number" name="id_users" placeholder="User ID" required>
            <input type="text" name="name" placeholder="Nama" required>
            <textarea name="message" placeholder="Pesan" required></textarea>
            <button type="submit">Kirim Testimonial</button>
        </form>

        <h2>Semua Testimonial</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonials as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['id']) ?></td>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['message']) ?></td>
                    <td><?= htmlspecialchars($t['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// === JSON Response (React or API use) ===
header("Content-Type: application/json");

// === POST: Tambah testimonial
if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input || !is_array($input)) {
        $input = $_POST;
    }

    $id_users = intval($input["id_users"] ?? 0);
    $name = trim($input["name"] ?? '');
    $message = trim($input["message"] ?? '');

    if (!$id_users || !$name || !$message) {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO testimonials (id_users, name, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $id_users, $name, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Testimonial berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit;
}

// === GET: Ambil semua testimonial
if ($method === "GET") {
    if (isset($_GET["id_users"])) {
        $id_users = intval($_GET["id_users"]);
        $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id_users = ? ORDER BY id DESC");
        $stmt->bind_param("i", $id_users);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query("SELECT * FROM testimonials ORDER BY id DESC");
    }

    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["status" => "success", "data" => $data]);
    exit;
}

// === Metode tidak dikenali
echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
exit;
