<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Ambil JSON dari body
$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];
$password = $data["password"];

// Cari user berdasarkan email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  // Verifikasi password
  if (password_verify($password, $user['password'])) {
    echo json_encode([
      "status" => "success",
      "message" => "Login successful",
      "name" => $user["name"],
      "email" => $user["email"] // tambahkan jika ingin dikirim ke frontend
    ]);
  } else {
    echo json_encode(["status" => "error", "message" => "Invalid password"]);
  }
} else {
  echo json_encode(["status" => "error", "message" => "User not found"]);
}
?>
