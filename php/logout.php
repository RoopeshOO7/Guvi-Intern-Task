<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';

use Predis\Client;

$redis = new Client();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $redis->del($email);  // Remove session from Redis
    session_unset();
    session_destroy();
}

header("Location: ../dashboard.html");
exit();
?>
