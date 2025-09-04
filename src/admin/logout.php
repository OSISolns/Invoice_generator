<?php
// Admin Logout
require_once '../admin_functions.php';

adminLogout();
header('Location: login.php');
exit;
?>
