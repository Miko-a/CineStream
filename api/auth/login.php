<?php

require '../config/database.php';
$data = json_decode(file_get_contents("php://input"));


$sql = "SELECT * FROM users WHERE email=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data->email]);
$user = $stmt->fetch();


if ($user && password_verify($data->password, $user['password_hash'])) {
    echo json_encode(["message" => "Login sukses", "user_id" => $user['user_id']]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Login gagal"]);
}

?>