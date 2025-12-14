<?php
require '../config/database.php';
$user_id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT * FROM watch_history WHERE user_id=? ORDER BY updated_at DESC");
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll());
?>