<?php

require '../config/database.php';
$id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id=?");
$stmt->execute([$id]);
echo json_encode($stmt->fetch());

?>