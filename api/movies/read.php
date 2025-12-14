<?php

require '../config/database.php';
$stmt = $pdo->query("SELECT * FROM movies");
echo json_encode($stmt->fetchAll());

?>