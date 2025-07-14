<?php
include 'connect.php';

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Ambil data JSON atau $_POST
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !is_array($input)) {
    $input = $_POST;
}

// Tampilkan Form HTML jika GET biasa dari browser
if ($method === 'GET' && !isset($_GET['id_users']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Form Reservasi</title>
        <style>
            body {
                font-family: Arial;
                background-color: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            form {
                background: white;
                padding: 20px;
                border-radius: 8px;
                width: 350px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            input, textarea {
                width: 100%;
                margin-bottom: 10px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 10px;
                width: 100%;
                border-radius: 4px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <form method="POST" action="reservation.php">
            <h3>Form Reservasi</h3>
            <input type="number" name="id_users" placeholder="User ID" required />
            <input type="text" name="name" placeholder="Nama Lengkap" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="text" name="phone" placeholder="Telepon" required />
            <input type="date" name="reservation_date" required />
            <input type="time" name="reservation_time" required />
            <input type="number" name="total_person" placeholder="Jumlah Tamu" required />
            <textarea name="message" placeholder="Pesan Tambahan"></textarea>
            <button type="submit">Kirim Reservasi</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Set response type untuk API
header("Content-Type: application/json");

switch ($method) {
    case 'GET':
        if (isset($_GET['id_users'])) {
            $id_users = intval($_GET['id_users']);
            $stmt = $conn->prepare("SELECT * FROM reservations WHERE id_users = ? ORDER BY id DESC");
            $stmt->bind_param("i", $id_users);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode(["status" => "success", "data" => $rows]);
        } else {
            echo json_encode(["status" => "error", "message" => "Parameter id_users diperlukan"]);
        }
        break;

    case 'POST':
        $id_users = $input['id_users'] ?? null;
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $date = $input['reservation_date'] ?? '';
        $time = $input['reservation_time'] ?? '';
        $guests = $input['total_person'] ?? '';
        $message = $input['message'] ?? '';

        if (!$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
            echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO reservations (id_users, name, email, phone, reservation_date, reservation_time, total_person, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssis", $id_users, $name, $email, $phone, $date, $time, $guests, $message);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi berhasil disimpan"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'PUT':
        $id = $input['id'] ?? null;
        $id_users = $input['id_users'] ?? null;
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $date = $input['reservation_date'] ?? '';
        $time = $input['reservation_time'] ?? '';
        $guests = $input['total_person'] ?? '';
        $message = $input['message'] ?? '';

        if (!$id || !$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE reservations SET id_users=?, name=?, email=?, phone=?, reservation_date=?, reservation_time=?, total_person=?, message=? WHERE id=?");
        $stmt->bind_param("isssssssi", $id_users, $name, $email, $phone, $date, $time, $guests, $message, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params);
        $id = $params['id'] ?? 0;

        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM reservations WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
        break;
}
?>
