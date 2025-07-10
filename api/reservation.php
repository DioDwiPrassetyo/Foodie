<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $result = $conn->query("SELECT * FROM reservations");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    echo json_encode($rows);
    break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $date = $data['date'];
    $time = $data['time'];
    $guests = $data['guests'];
    $message = $data['message'];
    $sql = "INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, total_person, message)
            VALUES ('$name', '$email', '$phone', '$date', '$time', '$guests', '$message')";
    echo $conn->query($sql) ? json_encode(["status" => "success"]) : json_encode(["status" => "error"]);
    break;

  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $date = $data['date'];
    $time = $data['time'];
    $guests = $data['guests'];
    $message = $data['message'];
    $sql = "UPDATE reservations SET name='$name', email='$email', phone='$phone',
            reservation_date='$date', reservation_time='$time', total_person='$guests', message='$message' WHERE id=$id";
    echo $conn->query($sql) ? json_encode(["status" => "success"]) : json_encode(["status" => "error"]);
    break;

  case 'DELETE':
    $id = $_GET['id'];
    $sql = "DELETE FROM reservations WHERE id=$id";
    echo $conn->query($sql) ? json_encode(["status" => "deleted"]) : json_encode(["status" => "error"]);
    break;
}
?>