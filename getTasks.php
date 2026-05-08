<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) 
{
    echo json_encode([
        "success" => false,
        "error" => "User not logged in"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

try 
{
    $sql = 
    "
        SELECT id, title, description, priority, status, task_date, reminder_time
        FROM tasks
        WHERE user_id = ?
        ORDER BY task_date ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);

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