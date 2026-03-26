<?php
session_start();
session_unset(); // Clear all variables
session_destroy(); // Destroy the session

// Go back to the login page
header("Location: login.php");
exit();
?>