<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "INSERT INTO movies (title, description, release_year, duration, video_url) VALUES (?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $data->title,
    $data->description,
    $data->release_year,
    $data->duration,
    $data->video_url
]);


echo json_encode(["message" => "Film ditambahkan"]);

?>