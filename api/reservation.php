<?php
include 'connect.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

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
    echo json_encode(["status" => "error", "message" => "id_users parameter is required"]);
  }
  break;

  case 'POST':
    $id_users = $input['id_users'] ?? null;
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $date = $input['reservation_date'] ?? '';
    $time = $input['reservation_time'] ?? '';
    $guests = $input['total_person'] ?? '';
    $message = $input['message'] ?? '';

    if (!$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
      echo json_encode(["status" => "error", "message" => "All fields are required to be filled in"]);
      exit;
    }

    $stmt = $conn->prepare("INSERT INTO reservations (id_users, name, email, phone, reservation_date, reservation_time, total_person, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssis", $id_users, $name, $email, $phone, $date, $time, $guests, $message);

    if ($stmt->execute()) {
      echo json_encode(["status" => "success"]);
    } else {
      echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    break;

  case 'PUT':
    $id = $input['id'] ?? null;
    $id_users = $input['id_users'] ?? null;
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $date = $input['reservation_date'] ?? '';
    $time = $input['reservation_time'] ?? '';
    $guests = $input['total_person'] ?? '';
    $message = $input['message'] ?? '';

    if (!$id || !$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
      echo json_encode(["status" => "error", "message" => "Incomplete data"]);
      exit;
    }

    $stmt = $conn->prepare("UPDATE reservations SET id_users=?, name=?, email=?, phone=?, reservation_date=?, reservation_time=?, total_person=?, message=? WHERE id=?");
    $stmt->bind_param("isssssssi", $id_users, $name, $email, $phone, $date, $time, $guests, $message, $id);

    if ($stmt->execute()) {
      echo json_encode(["status" => "success"]);
    } else {
      echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    break;

  case 'DELETE':
    parse_str($_SERVER['QUERY_STRING'], $params);
    $id = $params['id'] ?? 0;

    if (!$id) {
      echo json_encode(["status" => "error", "message" => "ID not found"]);
      exit;
    }

    $stmt = $conn->prepare("DELETE FROM reservations WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      echo json_encode(["status" => "success"]);
    } else {
      echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    break;

  default:
    echo json_encode(["status" => "error", "message" => "Method not supported"]);
    break;
}
?>
