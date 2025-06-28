<?php
require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as RedisClient;

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
  echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
  exit;
}

try {
  // MySQL Connection
  $mysqli = new mysqli("localhost", "guvi_user", "guvi@123", "guvi");
  if ($mysqli->connect_error) {
    throw new Exception("MySQL connection failed");
  }

  $stmt = $mysqli->prepare("SELECT username, email, dob, phone, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user || $user['password'] !== $password) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    exit;
  }

  // Generate session ID
  $sessionId = bin2hex(random_bytes(16));

  // Redis Connection & Set Session
  $redis = new RedisClient();
  $redis->set("session:$sessionId", $email);
  $redis->expire("session:$sessionId", 3600); // 1 hour TTL

  echo json_encode(['status' => 'success', 'sessionId' => $sessionId]);
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => 'Server error']);
}
