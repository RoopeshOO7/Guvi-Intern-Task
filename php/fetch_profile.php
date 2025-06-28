<?php
require '../vendor/autoload.php';

header('Content-Type: application/json');

if (!isset($_POST['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Email not provided']);
    exit();
}

$email = $_POST['email'];
error_log("FETCH_PROFILE.PHP received email: $email"); // ✅ log incoming email

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->guvi->users;

$doc = $collection->findOne(['email' => $email]);

if ($doc) {
    echo json_encode([
        'status' => 'success',
        'data' => [
            'username' => $doc['username'],
            'email' => $doc['email'],
            'dob' => $doc['dob'],
            'phone' => $doc['phone']
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}
?>
