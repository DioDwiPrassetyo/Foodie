<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$phone = $data['phone'] ?? '';

// Validasi
if (!$name || !$email || !$password || !$phone) {
    echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
    exit;
}

// Cek apakah email sudah terdaftar
$cek = $conn->query("SELECT * FROM users WHERE email = '$email'");
if ($cek->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email sudah digunakan"]);
    exit;
}

// Enkripsi password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$hashedPassword', '$phone')";
if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
}
?>
