<?php
header('Content-Type: application/json');

require '../config/database.php';

try {
    $sql = "SELECT genre_id, name FROM genres ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "genres" => $genres
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
