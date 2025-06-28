<?php
require_once("db.php");
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';

$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

$response = ['exists' => $stmt->num_rows > 0];
echo json_encode($response);
?>
