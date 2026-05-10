<?php
session_start();
require_once 'db.php';

//If username and password vars are set, attempt login
if(isset($username) && isset($password)){
    $passwordHash = password_hash($pass1, PASSWORD_DEFAULT);
    $sql = 'SELECT id passwordID FROM users WHERE username = :username AND passwordID = :passwordID';
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username'=> $username,':passwordID'=> $passwordHash]);

    //Fetshes results from SQL query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($pass1, $user['passHASH'])){
        echo "Login success";
        header("index.php");
    }
    else{
        echo "Error";
    }
}

?>


<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="stylesheet" href="SignIn.css" />
    <title>Login Page</title>
</head>
<body>
    <!--Top navigation bar-->
    <!--Kept consistant with About Page-->
    <?php include 'navbar.php'; ?>

    <form id="loginForm" action="">
        <fieldset>
            <legend>Log In</legend>
            <label for="user">Username</label>
            <input type="text" id="user" name="user" placeholder="Username" required>
            <label for="pass">Password</label>
            <input type="password" id="pass" name="pass" placeholder="********" required>
            <div class = "rememberMe">
                <p for="rememberMe">Remember Me?</p>
                <input type="checkbox" id="rememberMe" name="rememberMe">
            </div>
            <button type="button" onclick="OnLogin()">Login</button>
        </fieldset>
    </form>
    <script src="loginPage.js"></script>
</body>
</html>