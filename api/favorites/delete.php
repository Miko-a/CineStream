<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "DELETE FROM favorites WHERE user_id=? AND content_type=? AND content_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->user_id, $data->content_type, $data->content_id]);


echo json_encode(["message" => "Favorit dihapus"]);
?>