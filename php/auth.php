<?php
require 'mongo.php';

$token = $_POST['token'] ?? '';
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$email = $redis->get("MIM_SESSION:$token");

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
    exit;
}

$query = new MongoDB\Driver\Query(['email' => $email]);
$result = $mongo->executeQuery("mim.profiles", $query);
$user = current($result->toArray());

echo json_encode(['status' => 'success', 'user' => $user]);
?>