<?php
session_start();
header("Content-Type: application/json");

echo json_encode(
[
    "loggedIn" => isset($_SESSION['userID']),
    "userID" => $_SESSION['userID'] ?? null
]);