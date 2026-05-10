<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) 
{
    echo json_encode(["error" => "No data received"]);
    exit;
}

try 
{
    $sql = "INSERT INTO tasks 
    (userID, title, description, priority, status, task_date, reminder_time)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->execute(
    [
        $data['userID'] ?? 1,
        $data['title'],
        $data['description'],
        $data['priority'],
        $data['status'],
        $data['date'],
        $data['reminder_time'] ?? null  
    ]);

    echo json_encode(["success" => true]);

}
catch (Exception $e) 
{
    echo json_encode(["error" => $e->getMessage()]);
}
?>