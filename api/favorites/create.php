<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "INSERT IGNORE INTO favorites (user_id, content_type, content_id) VALUES (?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->user_id, $data->content_type, $data->content_id]);


echo json_encode(["message" => "Favorit ditambahkan"]);
?>