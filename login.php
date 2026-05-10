<?php
session_start();
require_once 'db.php';

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['user'] ?? '';
    $password = $_POST['pass'] ?? '';

    $sql = "SELECT users.id, userlogin.passHASH
            FROM users
            INNER JOIN userlogin
            ON users.id = userlogin.userID
            WHERE userlogin.username = :username";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['passHASH'])) 
    {

        $_SESSION['userID'] = $user['id'];

        header("Location: calendar.php");
        exit;
    }

    $passwordError = "Invalid username or password";
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

<form id="loginForm" action="" method="POST">
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
            <button type="submit">Login</button>
            <?php 
            if(isset(($passwordError)))
                echo "<u1 style='color:red;'>$passwordError</u1>"
                ?>
        </fieldset>
    </form>
    <script src="loginPage.js"></script>
</body>
</html>