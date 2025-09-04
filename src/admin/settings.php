<?php
require_once '../config.php';
require_once '../admin_functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$page_title = 'Settings';
include 'templates/dashboard.php';
?>
