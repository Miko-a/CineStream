<?php
require '../config/database.php';
$stmt = $pdo->query("SELECT * FROM series");
echo json_encode($stmt->fetchAll());
?>