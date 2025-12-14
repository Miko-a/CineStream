<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "UPDATE movies SET title=?, description=? WHERE movie_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->title, $data->description, $data->movie_id]);


echo json_encode(["message" => "Film diupdate"]);

?>