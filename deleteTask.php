<?php
session_start();
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$data['id'], $_SESSION['user_id']]);

echo json_encode(["success" => true]);