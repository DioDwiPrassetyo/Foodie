<?php
include 'connect.php';
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$isBrowser = $method === "GET" && !isset($_GET["id_users"]) && !isset($_SERVER['HTTP_X_REQUESTED_WITH']);

if ($isBrowser) {
    $result = $conn->query("SELECT * FROM testimonials ORDER BY id DESC");
    $testimonials = $result->fetch_all(MYSQLI_ASSOC);

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
                max-width: 800px;
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
            .actions button {
                margin-right: 5px;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Tambah Testimonial</h2>
        <form id="addForm" method="POST" action="testimonial.php">
            <input type="number" name="id_users" placeholder="User ID" required>
            <input type="text" name="name" placeholder="Nama" required>
            <textarea name="message" placeholder="Pesan" required></textarea>
            <button type="submit">Kirim Testimonial</button>
        </form>

        <div id="editForm" style="display:none;">
            <h2>Edit Testimonial</h2>
            <form onsubmit="submitEdit(event)">
                <input type="hidden" id="edit_id" name="id">
                <input type="number" id="edit_id_users" name="id_users" required>
                <input type="text" id="edit_name" name="name" required>
                <textarea id="edit_message" name="message" required></textarea>
                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>

        <h2>Semua Testimonial</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonials as $t): ?>
                <tr id="row-<?= $t['id'] ?>">
                    <td><?= htmlspecialchars($t['id']) ?></td>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['message']) ?></td>
                    <td><?= htmlspecialchars($t['created_at']) ?></td>
                    <td class="actions">
                        <button onclick='editTestimonial(<?= json_encode($t) ?>)'>Edit</button>
                        <button onclick='deleteTestimonial(<?= $t["id"] ?>)' style="color:red;">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    function editTestimonial(data) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_id_users').value = data.id_users;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_message').value = data.message;
        window.scrollTo(0, document.body.scrollHeight);
    }

    function deleteTestimonial(id) {
        if (!confirm("Hapus testimonial ini?")) return;
        fetch('testimonial.php?id=' + id, { method: 'DELETE' })
            .then(res => res.json())
            .then(resp => {
                alert(resp.message);
                if (resp.status === 'success') {
                    document.getElementById('row-' + id).remove();
                }
            });
    }

    function submitEdit(e) {
        e.preventDefault();
        const data = {
            id: document.getElementById('edit_id').value,
            id_users: document.getElementById('edit_id_users').value,
            name: document.getElementById('edit_name').value,
            message: document.getElementById('edit_message').value
        };

        fetch('testimonial.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            alert(resp.message);
            if (resp.status === 'success') location.reload();
        });
    }
    </script>
    </body>
    </html>
    <?php
    exit;
}

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !is_array($input)) {
    $input = $_POST;
}

// === POST ===
if ($method === "POST") {
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

// === PUT ===
if ($method === "PUT") {
    $id = intval($input["id"] ?? 0);
    $id_users = intval($input["id_users"] ?? 0);
    $name = trim($input["name"] ?? '');
    $message = trim($input["message"] ?? '');

    if (!$id || !$id_users || !$name || !$message) {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE testimonials SET id_users=?, name=?, message=? WHERE id=?");
    $stmt->bind_param("issi", $id_users, $name, $message, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Testimonial berhasil diperbarui"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit;
}

// === DELETE ===
if ($method === "DELETE") {
    parse_str($_SERVER['QUERY_STRING'], $params);
    $id = intval($params["id"] ?? 0);

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "ID tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Testimonial dihapus"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit;
}

// === GET ===
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

echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
?>
