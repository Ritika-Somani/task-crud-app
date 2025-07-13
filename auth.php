<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User not logged in, redirect to login
    header("Location: index.php");
    exit();
}
?>
