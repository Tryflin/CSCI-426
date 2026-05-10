<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id']))
{
    echo json_encode(
    [
        "success" => false,
        "error" => "Not logged in"
    ]);
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1)
{
    echo json_encode(
    [
        "success" => false,
        "error" => "Access denied"
    ]);
    exit;
}

try
{
    $sql = 
    "
        SELECT
            tasks.id,
            tasks.title,
            tasks.description,
            tasks.priority,
            tasks.status,
            tasks.task_date,
            tasks.reminder_time,
            users.username
        FROM tasks
        JOIN users ON tasks.user_id = users.id
        ORDER BY tasks.task_date ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

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