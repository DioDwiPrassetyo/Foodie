<?php
include 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

function respond($status, $message, $data = null) {
  echo json_encode([
    "status" => $status,
    "message" => $message,
    "data" => $data
  ]);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (!isset($_GET["id"])) {
    respond("error", "Missing ID parameter");
  }

  $id = $_GET["id"];
  $stmt = $conn->prepare("SELECT id, name, email, phone, profile_picture FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    respond("success", "User found", $user);
  } else {
    respond("error", "User not found");
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!isset($_POST["id"]) || !isset($_POST["name"]) || !isset($_POST["email"])) {
    respond("error", "Missing required fields");
  }

  $id = $_POST["id"];
  $name = $_POST["name"];
  $email = $_POST["email"];
  $phone = $_POST["phone"] ?? '';
  $profile_picture = null;

  if (!empty($_FILES["profile_picture"]["name"])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }

    $file_ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . "." . $file_ext;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
      $profile_picture = $target_file;
    } else {
      respond("error", "Failed to upload image.");
    }
  }

  if ($profile_picture) {
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, profile_picture=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $profile_picture, $id);
  } else {
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);
  }

  if ($stmt->execute()) {
    $query = $conn->prepare("SELECT id, name, email, phone, profile_picture FROM users WHERE id=?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $updatedUser = $result->fetch_assoc();

    respond("success", "User updated successfully", $updatedUser);
  } else {
    respond("error", "Failed to update user");
  }
}

respond("error", "Invalid request");
