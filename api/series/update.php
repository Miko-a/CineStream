<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));

try {
    // Start transaction
    $pdo->beginTransaction();

    // Update series
    $sql = "UPDATE series SET title=?, description=?, release_year=?, thumbnail_url=? WHERE series_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->title,
        $data->description,
        $data->release_year ?? null,
        $data->thumbnail_url ?? null,
        $data->series_id
    ]);

    // Update genres if provided
    if (isset($data->genres) && is_array($data->genres)) {
        // Delete existing genres
        $sql_delete = "DELETE FROM series_genres WHERE series_id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$data->series_id]);

        // Insert new genres
        $sql_genre = "INSERT INTO series_genres (series_id, genre_id) VALUES (?,?)";
        $stmt_genre = $pdo->prepare($sql_genre);
        
        foreach ($data->genres as $genre_id) {
            $stmt_genre->execute([$data->series_id, $genre_id]);
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Series diupdate"
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
