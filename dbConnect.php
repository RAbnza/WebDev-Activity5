<?php
$host = 'localhost';
$db   = 'contact_manager';
$user = 'root';
$pass = '1123';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>