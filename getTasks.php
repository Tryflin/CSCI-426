<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['userID'])) 
{
    echo json_encode([
        "success" => false,
        "error" => "User not logged in"
    ]);
    exit;
}

$userID = $_SESSION['userID'];

try 
{
    $sql = 
    "
        SELECT id, title, description, priority, status, task_date, reminder_time
        FROM tasks
        WHERE userID = ?
        ORDER BY task_date ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$userID]);

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(
    [
        "success" => true,
        "data" => $tasks
    ]);

} 
catch (Exception $e) 
{

    echo json_encode(
    [
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>