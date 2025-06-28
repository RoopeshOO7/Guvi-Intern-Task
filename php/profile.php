<?php
require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as RedisClient;
use MongoDB\Client as MongoClient;

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$sessionId = $data['sessionId'] ?? '';

if (!$sessionId) {
  echo json_encode(['status' => 'error', 'message' => 'Session ID missing']);
  exit;
}

try {
  $redis = new RedisClient();
  $email = $redis->get("session:$sessionId");

  if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Session expired']);
    exit;
  }

  $mongo = new MongoClient();
  $collection = $mongo->guvi->profiles;
  $user = $collection->findOne(['email' => $email]);

  if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
  }

  echo json_encode([
    'status' => 'success',
    'user' => [
      'username' => $user['username'] ?? '',
      'email' => $user['email'] ?? '',
      'dob' => $user['dob'] ?? '',
      'phone' => $user['phone'] ?? ''
    ]
  ]);
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => 'Server error']);
}
