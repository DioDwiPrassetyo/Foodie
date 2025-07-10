<?php
include 'connect.php'; // Pastikan koneksi MySQL $conn berhasil

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"] ?? '';
$email = $data["email"] ?? '';
$password = $data["password"] ?? '';

if (empty($name) || empty($email) || empty($password)) {
  echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
  exit;
}

// Cek apakah email sudah ada
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  echo json_encode(["status" => "error", "message" => "Email sudah terdaftar"]);
  exit;
}

// Simpan user baru
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
  echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
  echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
}
?>
