<?php

session_start();

// Remove admin session
unset($_SESSION["admin_id"]);
unset($_SESSION["admin_name"]);

// Destroy session
session_destroy();

// Return to admin login
header("Location: admin-login.php");
exit;

?>