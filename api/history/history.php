<?php
require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "INSERT INTO watch_history (user_id, content_type, content_id, last_position)
VALUES (?,?,?,?)
ON DUPLICATE KEY UPDATE last_position=?, updated_at=NOW()";
$stmt = $pdo->prepare($sql);
$stmt->execute([
$data->user_id,
$data->content_type,
$data->content_id,
$data->last_position,
$data->last_position
]);


echo json_encode(["message" => "Riwayat ditersimpan"]);
?>