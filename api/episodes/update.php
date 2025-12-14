<?php
require '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$episodeId = $_POST['episode_id'] ?? null;
$season = $_POST['season'] ?? null;
$episodeNumber = $_POST['episode_number'] ?? null;
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? '';
$videoUrl = $_POST['video_url'] ?? '';

if (!$episodeId || !$title) {
    http_response_code(400);
    echo json_encode(['error' => 'episode_id and title are required']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE episodes SET season = ?, episode_number = ?, title = ?, description = ?, video_url = ? WHERE episode_id = ?');
    $stmt->execute([
        intval($season),
        intval($episodeNumber),
        $title,
        $description,
        $videoUrl,
        intval($episodeId)
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update episode']);
}
?>