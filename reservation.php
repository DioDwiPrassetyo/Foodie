<?php
include 'connect.php';
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !is_array($input)) {
    $input = $_POST;
}

// 💻 Tambahan tampilan admin (tanpa mengubah form yang sudah ada)
if ($method === 'GET' && !isset($_GET['id_users']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header("Content-Type: text/html");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Form Reservasi</title>
        <style>
    body {
        font-family: Arial;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        width: 100%;
    }

    form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 350px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    input, textarea {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 4px;
        cursor: pointer;
    }

    table {
        border-collapse: collapse;
        width: 90%;
        background: #fff;
        margin-top: 40px;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    #reservation-table {
        max-width: 1000px;
        margin-bottom: 50px;
    }
</style>

    </head>
    <body>
        <div class="form-wrapper">
            <form method="POST" action="reservation.php">
                <h3>Form Reservasi</h3>
                <input type="number" name="id_users" placeholder="User ID" required />
                <input type="text" name="name" placeholder="Nama Lengkap" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="text" name="phone" placeholder="Telepon" required />
                <input type="date" name="reservation_date" required />
                <input type="time" name="reservation_time" required />
                <input type="number" name="total_person" placeholder="Jumlah Tamu" required />
                <textarea name="message" placeholder="Pesan Tambahan"></textarea>
                <button type="submit">Kirim Reservasi</button>
            </form>
        </div>

        <hr><h3>Data Semua Reservasi</h3>
        <div id="reservation-table">Memuat data...</div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('reservation.php?id_users=0')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderTable(data.data);
                    } else {
                        document.getElementById('reservation-table').innerHTML = '<p>Tidak ada data.</p>';
                    }
                });

            function renderTable(data) {
                let html = `<table><thead>
                    <tr>
                        <th>ID</th><th>User</th><th>Nama</th><th>Email</th><th>Telepon</th>
                        <th>Tanggal</th><th>Jam</th><th>Tamu</th><th>Pesan</th><th>Aksi</th>
                    </tr></thead><tbody>`;
                data.forEach(row => {
                    html += `<tr>
                        <td>${row.id}</td>
                        <td>${row.id_users}</td>
                        <td>${row.name}</td>
                        <td>${row.email}</td>
                        <td>${row.phone}</td>
                        <td>${row.reservation_date}</td>
                        <td>${row.reservation_time}</td>
                        <td>${row.total_person}</td>
                        <td>${row.message}</td>
                        <td>
                            <button onclick='editReservation(${JSON.stringify(row)})'>Edit</button>
                            <button onclick='deleteReservation(${row.id})' style="color:red;">Delete</button>
                        </td>
                    </tr>`;
                });
                html += `</tbody></table>
                <div id="edit-form" style="margin-top:30px; display:none;">
                    <h3>Edit Reservasi</h3>
                    <form onsubmit="submitEditForm(event)">
                        <input type="hidden" id="edit_id" name="id">
                        <input type="number" id="edit_id_users" name="id_users" required><br>
                        <input type="text" id="edit_name" name="name" required><br>
                        <input type="email" id="edit_email" name="email" required><br>
                        <input type="text" id="edit_phone" name="phone" required><br>
                        <input type="date" id="edit_reservation_date" name="reservation_date" required><br>
                        <input type="time" id="edit_reservation_time" name="reservation_time" required><br>
                        <input type="number" id="edit_total_person" name="total_person" required><br>
                        <textarea id="edit_message" name="message"></textarea><br>
                        <button type="submit">Simpan</button>
                    </form>
                </div>`;
                document.getElementById('reservation-table').innerHTML = html;
            }

            window.editReservation = function(data) {
                document.getElementById('edit-form').style.display = 'block';
                for (let key in data) {
                    const el = document.getElementById('edit_' + key);
                    if (el) el.value = data[key];
                }
                window.scrollTo(0, document.body.scrollHeight);
            }

            window.deleteReservation = function(id) {
                if (!confirm("Hapus reservasi ini?")) return;
                fetch('reservation.php?id=' + id, { method: 'DELETE' })
                    .then(res => res.json())
                    .then(resp => {
                        alert(resp.message);
                        if (resp.status === 'success') location.reload();
                    });
            }

            window.submitEditForm = function(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                const jsonData = {};
                formData.forEach((v, k) => jsonData[k] = v);

                fetch('reservation.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(jsonData)
                })
                .then(res => res.json())
                .then(resp => {
                    alert(resp.message);
                    if (resp.status === 'success') location.reload();
                });
            }
        });
        </script>
    </body>
    </html>
    <?php
    exit;
}

header("Content-Type: application/json");

switch ($method) {
    case 'GET':
        if (isset($_GET['id_users'])) {
            $id_users = intval($_GET['id_users']);

            if ($id_users === 0) {
                $stmt = $conn->prepare("SELECT * FROM reservations ORDER BY id DESC");
            } else {
                $stmt = $conn->prepare("SELECT * FROM reservations WHERE id_users = ? ORDER BY id DESC");
                $stmt->bind_param("i", $id_users);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode(["status" => "success", "data" => $rows]);
        } else {
            echo json_encode(["status" => "error", "message" => "Parameter id_users diperlukan"]);
        }
        break;

    case 'POST':
        $id_users = $input['id_users'] ?? null;
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $date = $input['reservation_date'] ?? '';
        $time = $input['reservation_time'] ?? '';
        $guests = $input['total_person'] ?? '';
        $message = $input['message'] ?? '';

        if (!$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
            echo json_encode(["status" => "error", "message" => "Semua field wajib diisi"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO reservations (id_users, name, email, phone, reservation_date, reservation_time, total_person, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssis", $id_users, $name, $email, $phone, $date, $time, $guests, $message);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi berhasil disimpan"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'PUT':
        $id = $input['id'] ?? null;
        $id_users = $input['id_users'] ?? null;
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $date = $input['reservation_date'] ?? '';
        $time = $input['reservation_time'] ?? '';
        $guests = $input['total_person'] ?? '';
        $message = $input['message'] ?? '';

        if (!$id || !$id_users || !$name || !$email || !$phone || !$date || !$time || !$guests) {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE reservations SET id_users=?, name=?, email=?, phone=?, reservation_date=?, reservation_time=?, total_person=?, message=? WHERE id=?");
        $stmt->bind_param("isssssssi", $id_users, $name, $email, $phone, $date, $time, $guests, $message, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $params);
        $id = $params['id'] ?? 0;

        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM reservations WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Reservasi dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak didukung"]);
        break;
}
?>
