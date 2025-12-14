<?php
header('Content-Type: application/json');

session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['user_id']) || !isset($data['role'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

// Set session data
$_SESSION['user_id'] = $data['user_id'];
$_SESSION['email'] = $data['email'] ?? '';
$_SESSION['role'] = $data['role'];
$_SESSION['login_time'] = time();

echo json_encode(['success' => true, 'message' => 'Session set']);
?>
