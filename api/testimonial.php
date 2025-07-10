<?php
include 'connect.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $result = $conn->query("SELECT * FROM testimonials");
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
    $rating = $data['rating'];
    $comment = $data['comment'];
    $sql = "INSERT INTO testimonials (name, email, rating, comment)
            VALUES ('$name', '$email', '$rating', '$comment')";
    echo $conn->query($sql) ? json_encode(["status" => "success"]) : json_encode(["status" => "error"]);
    break;

  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $rating = $data['rating'];
    $comment = $data['comment'];
    $sql = "UPDATE testimonials SET name='$name', email='$email', rating='$rating', comment='$comment' WHERE id=$id";
    echo $conn->query($sql) ? json_encode(["status" => "success"]) : json_encode(["status" => "error"]);
    break;

  case 'DELETE':
    $id = $_GET['id'];
    $sql = "DELETE FROM testimonials WHERE id=$id";
    echo $conn->query($sql) ? json_encode(["status" => "deleted"]) : json_encode(["status" => "error"]);
    break;
}
?>