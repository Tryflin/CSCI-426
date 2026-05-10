<?php
session_start();
require_once 'db.php';

if(isset($username) && isset($password)){
    
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
    <nav>
        <span class="logo">Task Management System</span>

        <ul>
            <li><a href="calendar.html">Calendar</a></li>
            <li><a href="index.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>

        <a href="signup.html" class="signup-button">Sign Up</a>
    </nav>

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