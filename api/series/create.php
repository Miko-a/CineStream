<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "INSERT INTO series (title, description, release_year, thumbnail_url) VALUES (?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
$data->title,
$data->description,
$data->release_year,
$data->thumbnail_url
]);


echo json_encode(["message" => "Series berhasil ditambahkan"]);
?>