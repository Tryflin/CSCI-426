<?php
session_start();
//this gives us $conn, for DB connection
require 'db.php';

$passwordError = "";
$usernameError = "";

//Catch html request method, then do verification on server
if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST["Name"] ?? "";
        $email = $_POST["Email"] ??"";
        $pass1 = $_POST["pass1"] ??"";
        $pass2 = $_POST["pass2"] ??"";

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute([":username" => $username]);

        //Checks if username is taken, or if passwords dont match
        if($stmt->rowCount() > 0){
            $usernameError = "This username has already been taken!";
        } else{
            $usernameError = "";
        }
        if($pass1 != $pass2){
            $passwordError = "Passwords do not match!";
        } else{
            $passwordError = "";
        }

        //if no problems making account
        if($passwordError == "" and $usernameError == ""){
            $passwordHash = password_hash($pass1, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(username, email, passwordID) VALUES (:username, :email, :passwordID)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([":username" => $username, ":email" => $email, ":passwordID" => $passwordHash]);
        }
    }
    //Starts out with signup, switches to error page if passwords dont match, then moves to next page

//Built in password hash function, very helpful!
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="stylesheet" href="SignIn.css" />
    <title>Signup Page</title>
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
        
        <!--Reused signup button for login-->
        <a href="login.html" class="signup-button">Log In</a>
    </nav>

    <form action="signup.php" method="POST">
        <fieldset>
            <legend>Create Your Account</legend>
            <label for="Name">Enter Your User Name</label>
            <input id="Name" name="Name" placeholder="Username" required></input>

            <label for="Email">Enter Your Recovery Email</label>
            <input id="Email" name="Email" placeholder="Email"></input>
            
            <label for="pass1">Enter Your Password</label>
            <input id="pass1" name="pass1" type="password" placeholder="********" required></input>
            <?php if (!empty($passwordError)) echo "<u1 style='color:red;'>$passwordError</u1>"; ?>
            
            <label for="pass2">Confirm Your Password</label>
            <input id="pass2" name="pass2" type="password" placeholder="********" required></input>
        <button type="submit">Sign Up</button>
        </fieldset>
    </form>
</body>
</html>