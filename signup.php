<?php
session_start();
//this gives us $conn, for DB connection
require 'db.php';

$error = "";
//Catch html request method, then do verification on server
if($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST["Name"] ?? "";
        $email = $_POST["Email"] ??"";
        $pass1 = $_POST["pass1"] ??"";
        $pass2 = $_POST["pass2"] ??"";
        if($pass1 != $pass2){
            $error = "Passwords do not match";
            require 'signupError.html';
        }
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
    }
    //Starts out with signup, switches to error page if passwords dont match, then moves to next page
else{
    require 'signup.html';
}
//Built in password hash function, very helpful!
?>
