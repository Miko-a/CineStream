<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));

try {
    // Start transaction
    $pdo->beginTransaction();

    // Insert series
    $sql = "INSERT INTO series (title, description, release_year, thumbnail_url) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->title,
        $data->description,
        $data->release_year,
        $data->thumbnail_url
    ]);

    $series_id = $pdo->lastInsertId();

    // Insert genres if provided
    if (!empty($data->genres) && is_array($data->genres)) {
        $sql_genre = "INSERT INTO series_genres (series_id, genre_id) VALUES (?,?)";
        $stmt_genre = $pdo->prepare($sql_genre);
        
        foreach ($data->genres as $genre_id) {
            $stmt_genre->execute([$series_id, $genre_id]);
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Series berhasil ditambahkan",
        "series_id" => $series_id
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>