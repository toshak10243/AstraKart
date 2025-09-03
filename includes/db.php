<?php
// includes/db.php

$servername = "localhost";   // MySQL service host
$username   = "root";        // MySQL username
$password   = "1234";            // MySQL password (set apne hisaab se)
$database   = "ecommerce";   // Database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
