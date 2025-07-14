<?php
include 'connect.php';
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// === Logout jika ada parameter ?logout=true
if (isset($_GET['logout']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    session_destroy();
    header("Location: login.php");
    exit;
}

// === FORM HTML JIKA DIAKSES DARI BROWSER TANPA FETCH
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login Page</title>
        <style>
            body {
                font-family: Arial;
                background-color: #f8f8f8;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            form, .success {
                background: #fff;
                padding: 24px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                width: 300px;
                text-align: center;
            }
            input, button {
                width: 100%;
                padding: 10px;
                margin: 8px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 20px;
            }
            .btn-group button {
                width: 100%;
                padding: 10px;
                border: none;
                background: #007bff;
                color: white;
                border-radius: 4px;
                cursor: pointer;
            }
            .btn-group .logout {
                background-color: #dc3545;
            }
        </style>
    </head>
    <body>
        <?php if (!isset($_SESSION['user'])): ?>
        <form method="POST" action="login.php">
            <h3>Login Form</h3>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>
        <?php else: ?>
        <div class="success">
            <h3>Login Berhasil</h3>
            <p>Halo, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</p>
            <div class="btn-group">
                <button onclick="window.location.href='reservation.php'">Go to Reservation</button>
                <button onclick="window.location.href='users.php'">Go to Users</button>
                <button onclick="window.location.href='testimonial.php'">Go to Testimonials</button>
                <button class="logout" onclick="window.location.href='login.php?logout=true'">Logout</button>
            </div>
        </div>
        <?php endif; ?>
    </body>
    </html>
    <?php
    exit;
}

// === RESPON JSON UNTUK API ===
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!$data || !is_array($data)) {
        $data = $_POST;
    }

    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');

    if (!$email || !$password) {
        echo json_encode(["status" => "error", "message" => "Email dan password harus diisi"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                "id" => $user["id"],
                "name" => $user["name"],
                "email" => $user["email"],
                "phone" => $user["phone"]
            ];

            echo json_encode([
                "status" => "success",
                "message" => "Login berhasil",
                "user" => $_SESSION['user']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
    }
    exit;
}

// === Metode lain selain POST
echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
exit;
