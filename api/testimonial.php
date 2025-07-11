<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Koneksi database
$conn = new mysqli("localhost", "root", "", "foodie");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]);
    exit;
}

// Ambil metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// === POST: Tambah testimonial ===
if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($input['id_users'], $input['name'], $input['message']) ||
        empty($input['id_users']) ||
        empty(trim($input['name'])) ||
        empty(trim($input['message']))
    ) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        exit;
    }

    $id_users = intval($input['id_users']);
    $name = $conn->real_escape_string($input['name']);
    $message = $conn->real_escape_string($input['message']);

    $stmt = $conn->prepare("INSERT INTO testimonials (id_users, name, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $id_users, $name, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Testimonial berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan testimonial: " . $stmt->error]);
    }

    $stmt->close();
    exit;
}

// === GET: Ambil testimonial ===
if ($method === "GET") {
    if (isset($_GET['id_users'])) {
        $id_users = intval($_GET['id_users']);
        $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id_users = ? ORDER BY id DESC");
        $stmt->bind_param("i", $id_users);
    } else {
        $stmt = $conn->prepare("SELECT * FROM testimonials ORDER BY id DESC");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $rows]);
    $stmt->close();
    exit;
}

// === Jika metode tidak didukung ===
echo json_encode([
    "status" => "error",
    "message" => "Metode HTTP tidak didukung"
]);
exit;
?>
