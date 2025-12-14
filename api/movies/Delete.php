<?php

require '../config/database.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM movies WHERE movie_id=?")->execute([$id]);


echo json_encode(["message" => "Film dihapus"]);

?>