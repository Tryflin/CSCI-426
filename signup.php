<?php
session_start();
require_once 'db.php';

$passwordError = "";
$usernameError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["Name"] ?? "");
    $email = trim($_POST["Email"] ?? "");
    $pass1 = $_POST["pass1"] ?? "";
    $pass2 = $_POST["pass2"] ?? "";

    // Check if username exists in users table
    $sql = "SELECT id FROM users WHERE NAME = :NAME";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":NAME" => $username]);

    if ($stmt->rowCount() > 0) {
        $usernameError = "This username has already been taken!";
    }

    if ($pass1 !== $pass2) {
        $passwordError = "Passwords do not match!";
    }

    // If no errors, create account
    if ($passwordError === "" && $usernameError === "") {

        $passwordHash = password_hash($pass1, PASSWORD_DEFAULT);

        // 1. Insert into users table
        $sql = "INSERT INTO users (NAME, email, password)
                VALUES (:NAME, :email, :password)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":NAME" => $username,
            ":email" => $email,
            ":password" => $passwordHash
        ]);

        // Get new user ID
        $userID = $conn->lastInsertId();

        // 2. Insert into userlogin table (REQUIRED by your system)
        $sql = "INSERT INTO userlogin (username, passHASH, userID)
                VALUES (:username, :passHASH, :userID)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":username" => $username,
            ":passHASH" => $passwordHash,
            ":userID" => $userID
        ]);

        // Set session
        $_SESSION["username"] = $username;
        $_SESSION["userID"] = $userID;

        header("Location: calendar.php");
        exit;
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="stylesheet" href="SignIn.css" />
    <title>Signup Page</title>
</head>
<body>

<nav>
    <span class="logo">Task Management System</span>

    <ul>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="index.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>

    <a href="login.php" class="signup-button">Log In</a>
</nav>

<form action="signup.php" method="POST">
    <fieldset>
        <legend>Create Your Account</legend>

        <label for="Name">Enter Your User Name</label>
        <input id="Name" name="Name" placeholder="Username" required>

        <label for="Email">Enter Your Recovery Email</label>
        <input id="Email" name="Email" placeholder="Email">

        <label for="pass1">Enter Your Password</label>
        <input id="pass1" name="pass1" type="password" placeholder="********" required>
        <?php if (!empty($passwordError)) echo "<p style='color:red;'>$passwordError</p>"; ?>

        <label for="pass2">Confirm Your Password</label>
        <input id="pass2" name="pass2" type="password" placeholder="********" required>

        <button type="submit">Sign Up</button>
    </fieldset>
</form>

</body>
</html>
