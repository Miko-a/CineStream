<?php
require '../config/api.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    $res = apiRequest("/auth/login","POST",[
        "email"=>$_POST['email'],
        "password"=>$_POST['password']
    ]);

    if(isset($res['user_id'])){
        $_SESSION['user_id'] = $res['user_id'];
        header("Location: ../pages/home.php");
    }
}
?>
<form method="POST">
    <h3>Login</h3>
    Email <input name="email"><br>
    Password <input type="password" name="password"><br>
    <button>Login</button>
</form>
