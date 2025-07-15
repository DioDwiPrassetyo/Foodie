<?php
include 'connect.php';

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// FORM HTML jika akses dari browser langsung (bukan dari fetch/XHR)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Register Page</title>
        <style>
            body {
                font-family: Arial;
                background-color: #f8f8f8;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            form {
                background: #fff;
                padding: 24px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                width: 320px;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 8px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                background-color: #007bff;
                color: white;
                padding: 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <form method="POST" action="register.php">
            <h3>Register Form</h3>
            <input type="text" name="name" placeholder="Nama Lengkap" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="text" name="phone" placeholder="No Telepon" required />
            <button type="submit">Register</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Selain POST → ditolak
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Method tidak diizinkan"]);
    exit;
}

// Set response JSON untuk API
header("Content-Type: application/json");

// Ambil dari JSON atau $_POST (form)
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
if (!$data || !is_array($data)) {
    $data = $_POST;
}

// Ambil dan validasi data
$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$phone    = trim($data['phone'] ?? '');

if (!$name || !$email || !$password || !$phone) {
    echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
    exit;
}

// Cek duplikat email
$cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
$cek->bind_param("s", $email);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email sudah digunakan"]);
    exit;
}

// Enkripsi dan simpan
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
}
?>
