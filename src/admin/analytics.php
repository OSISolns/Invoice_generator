<?php
require_once '../config.php';
require_once '../admin_functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$page_title = 'Analytics Dashboard';
include 'templates/dashboard.php';
?>
