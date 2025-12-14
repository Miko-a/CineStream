<?php
header('Content-Type: application/json');

require '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->name) || empty($data->name)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => "Nama genre harus diisi"
    ]);
    exit;
}

try {
    // Check if genre already exists
    $sql_check = "SELECT genre_id FROM genres WHERE name = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$data->name]);
    
    if ($stmt_check->rowCount() > 0) {
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            "success" => true,
            "message" => "Genre sudah ada",
            "genre_id" => $result['genre_id'],
            "is_new" => false
        ]);
        exit;
    }

    // Create new genre
    $sql = "INSERT INTO genres (name) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data->name]);
    
    $genre_id = $pdo->lastInsertId();
    
    echo json_encode([
        "success" => true,
        "message" => "Genre berhasil ditambahkan",
        "genre_id" => $genre_id,
        "is_new" => true
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
