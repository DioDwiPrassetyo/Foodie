<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "testing";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal terhubung ke database: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
