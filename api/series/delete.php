<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$series_id = $input['series_id'] ?? null;

if (!$series_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Series ID is required']);
    exit;
}

try {
    $pdo->prepare("DELETE FROM series WHERE series_id = ?")->execute([$series_id]);
    echo json_encode(["success" => true, "message" => "Series berhasil dihapus"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menghapus series: ' . $e->getMessage()]);
}
?>