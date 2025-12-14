<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

// Validation
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email dan password harus diisi']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format email tidak valid']);
    exit;
}

try {
    $sql = "SELECT user_id, email, password_hash, role FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        echo json_encode([
            "success" => true,
            "message" => "Login sukses",
            "user_id" => $user['user_id'],
            "email" => $user['email'],
            "role" => $user['role'] ?? 'user'
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Email atau password salah"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
}
?>