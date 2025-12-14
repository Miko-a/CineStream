<?php
require '../config/database.php';
$series_id = $_GET['series_id'];
$stmt = $pdo->prepare("SELECT * FROM episodes WHERE series_id=? ORDER BY season, episode_number");
$stmt->execute([$series_id]);
echo json_encode($stmt->fetchAll());
?>