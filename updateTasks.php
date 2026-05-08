<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(
    [
        "success" => false,
        "error" => "Not logged in"
    ]);
    exit;
}

$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !$status) 
{
    echo json_encode(
    [
        "success" => false,
        "error" => "Missing data"
    ]);
    exit;
}

try 
{
    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$status, $id, $_SESSION['user_id']]);

    echo json_encode(
    [
        "success" => true
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