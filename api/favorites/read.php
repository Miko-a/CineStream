<?php
require '../config/database.php';
$user_id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id=?");
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll());
?>