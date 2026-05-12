<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['user'] ?? '');
    $password = trim($_POST['pass'] ?? '');

    $sql = "SELECT userID, passHASH FROM userlogin WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['passHASH'])) {

        $_SESSION['userID'] = $user['userID'];

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
if (isset($passwordError)) {
    echo "<p style='color:red;'>$passwordError</p>";
}
?>
        </fieldset>
    </form>
</body>
</html>
