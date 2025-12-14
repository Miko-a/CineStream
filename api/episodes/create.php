<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "INSERT INTO episodes (series_id, season, episode_number, title, duration, video_url)
VALUES (?,?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
$data->series_id,
$data->season,
$data->episode_number,
$data->title,
$data->duration,
$data->video_url
]);


echo json_encode(["message" => "Episode ditambahkan"]);
?>