<?php
// Start the session
session_start();

// Check if a username exists in the session
if (!isset($_SESSION['username'])) {
    //redirect to the login.php page
    header("Location: login.php");
    // end the script
    exit;
}
?>