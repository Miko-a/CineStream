<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));

try {
    // Start transaction
    $pdo->beginTransaction();

    // Insert movie
    $sql = "INSERT INTO movies (title, description, release_year, duration, video_url) VALUES (?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->title,
        $data->description,
        $data->release_year,
        $data->duration,
        $data->video_url
    ]);

    $movie_id = $pdo->lastInsertId();

    // Insert genres if provided
    if (!empty($data->genres) && is_array($data->genres)) {
        $sql_genre = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?,?)";
        $stmt_genre = $pdo->prepare($sql_genre);
        
        foreach ($data->genres as $genre_id) {
            $stmt_genre->execute([$movie_id, $genre_id]);
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Film ditambahkan",
        "movie_id" => $movie_id
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