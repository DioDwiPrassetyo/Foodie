<?php
include 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// CORS Preflight
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// === FORM HTML jika diakses langsung dari browser tanpa parameter
if ($_SERVER["REQUEST_METHOD"] === "GET" && !isset($_GET["id"]) && !isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Update Profil</title>
        <style>
            body { font-family: Arial; background-color: #f8f8f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
            form { background: #fff; padding: 24px; border-radius: 8px; width: 350px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; }
            button { background-color: #007bff; color: white; padding: 10px; border: none; width: 100%; border-radius: 4px; }
        </style>
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data" action="users.php">
            <h3>Update Profil</h3>
            <input type="number" name="id" placeholder="ID User" required />
            <input type="text" name="name" placeholder="Nama Lengkap" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="text" name="phone" placeholder="No Telepon" />
            <input type="file" name="profile_picture" accept="image/*" />
            <button type="submit">Update</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// === Set response JSON
header("Content-Type: application/json");

// === GET (fetch user by ID)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $stmt = $conn->prepare("SELECT id, name, email, phone, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo json_encode(["status" => "success", "data" => $user]);
    } else {
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
    }
    exit;
}

// === POST (update user data)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $profile_picture = null;

    if (!$id || !$name || !$email) {
        echo json_encode(["status" => "error", "message" => "Field id, name, dan email wajib diisi"]);
        exit;
    }

    // Upload gambar jika dikirim
    if (!empty($_FILES["profile_picture"]["name"])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $filename = uniqid("profile_") . "." . $ext;
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            echo json_encode(["status" => "error", "message" => "Upload gambar gagal"]);
            exit;
        }
    }

    // Query update
    if ($profile_picture) {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, profile_picture=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $profile_picture, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    exit;
}

// === Default
echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
