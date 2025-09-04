<?php
// Database configuration
$host = 'localhost';
$dbname = 'invoice_generator';
$username = 'invoice_user';
$password = 'invoice_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
