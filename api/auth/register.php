<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$password = password_hash($data->password, PASSWORD_BCRYPT);
$sql = "INSERT INTO users (username, email, password_hash) VALUES (?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->username, $data->email, $password]);


echo json_encode(["message" => "Register berhasil"]);

?>