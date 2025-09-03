<?php
$host = "localhost";
$user = "root";
$pass = "1234";
$dbname = "ecommerce"; // apna database naam

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
