<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$movie_id = $input['movie_id'] ?? null;

if (!$movie_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Movie ID is required']);
    exit;
}

try {
    $pdo->prepare("DELETE FROM movies WHERE movie_id = ?")->execute([$movie_id]);
    echo json_encode(["success" => true, "message" => "Film berhasil dihapus"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menghapus film: ' . $e->getMessage()]);
}
?>