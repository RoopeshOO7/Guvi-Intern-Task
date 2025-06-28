<?php
require __DIR__ . '/../vendor/autoload.php';
use MongoDB\Client as MongoClient;

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$dob = $data['dob'] ?? '';
$phone = $data['phone'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$email || !$dob || !$phone || !$password) {
  echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
  exit;
}

$mysqli = new mysqli("localhost", "guvi_user", "guvi@123", "guvi");
if ($mysqli->connect_error) {
  echo json_encode(['status' => 'error', 'message' => 'MySQL connection failed']);
  exit;
}

$stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
  echo json_encode(['status' => 'error', 'message' => 'Account already exists']);
  exit;
}

$stmt = $mysqli->prepare("INSERT INTO users (username, email, dob, phone, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $dob, $phone, $password);
if ($stmt->execute()) {
  try {
    $mongo = new MongoClient();
    $collection = $mongo->guvi->profiles;
    $collection->insertOne([
      'username' => $username,
      'email' => $email,
      'dob' => $dob,
      'phone' => $phone
    ]);
    echo json_encode(['status' => 'success']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'MongoDB insertion failed']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Signup failed']);
}
