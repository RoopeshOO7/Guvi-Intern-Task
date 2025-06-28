<?php
session_start();
$_SESSION['test'] = 'Redis is working!';
echo "Session ID: " . session_id();
?>
