<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));

try {
    // Start transaction
    $pdo->beginTransaction();

    // Update movie
    $sql = "UPDATE movies SET title=?, description=?, release_year=?, duration=?, video_url=? WHERE movie_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->title,
        $data->description,
        $data->release_year ?? null,
        $data->duration ?? null,
        $data->video_url ?? null,
        $data->movie_id
    ]);

    // Update genres if provided
    if (isset($data->genres) && is_array($data->genres)) {
        // Delete existing genres
        $sql_delete = "DELETE FROM movie_genres WHERE movie_id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$data->movie_id]);

        // Insert new genres
        $sql_genre = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?,?)";
        $stmt_genre = $pdo->prepare($sql_genre);
        
        foreach ($data->genres as $genre_id) {
            $stmt_genre->execute([$data->movie_id, $genre_id]);
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Film diupdate"
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