<?php
// logout.php
session_start();

// Sabhi session variables unset karna
$_SESSION = array();

// Session destroy karna
session_destroy();

// Admin ko login page par redirect karna
header("Location: index.php");
exit();
?>
