<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

// Validation
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username, email, dan password harus diisi']);
    exit;
}

if (strlen($username) < 3) {
    http_response_code(400);
    echo json_encode(['error' => 'Username minimal 3 karakter']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format email tidak valid']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Password minimal 6 karakter']);
    exit;
}

if ($password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['error' => 'Password tidak cocok']);
    exit;
}

// Check if email already exists
try {
    $checkSql = "SELECT user_id FROM users WHERE email = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$email]);
    
    if ($checkStmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Email sudah terdaftar']);
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO users (username, email, password_hash, role, created_at) VALUES (?, ?, ?, 'user', NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $password_hash]);

    echo json_encode([
        "success" => true,
        "message" => "Registrasi berhasil! Silakan login.",
        "user_id" => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
}
?>