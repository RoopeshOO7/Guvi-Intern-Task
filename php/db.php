<?php
$host = "localhost";
$username = "mimuser";
$password = "mim@123";
$database = "guvi";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

