<?php
require '../config/database.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM series WHERE series_id=?")->execute([$id]);


echo json_encode(["message" => "Series dihapus"]);
?>