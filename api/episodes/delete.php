<?php
require '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$episodeId = $_POST['episode_id'] ?? null;
if (!$episodeId) {
    http_response_code(400);
    echo json_encode(['error' => 'episode_id is required']);
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM episodes WHERE episode_id = ?');
    $stmt->execute([$episodeId]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete episode']);
}
?>