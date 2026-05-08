<?php
require 'db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) 
{
    echo json_encode(["error" => "No data received"]);
    exit;
}

$sql = "INSERT INTO tasks (user_id, title, description, priority, status, task_date)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->execute(
[
    $data['user_id'] ?? 1,
    $data['title'],
    $data['description'],
    $data['priority'],
    $data['status'],
    $data['date']
]);

echo json_encode(["message" => "Task saved"]);
?>