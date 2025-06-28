<?php
require 'mongo.php';

$email = $_POST['email'];
$username = $_POST['username'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];

$result = $collection->updateOne(
    ['email' => $email],
    ['$set' => [
        'username' => $username,
        'dob' => $dob,
        'phone' => $phone
    ]]
);

if ($result->getModifiedCount()) {
    echo "✅ Profile updated successfully!";
} else {
    echo "⚠️ No changes made or update failed.";
}
?>
