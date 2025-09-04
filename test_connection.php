<?php
/**
 * Database Connection Test Script
 * This script will help diagnose database connection issues
 */

echo "<h2>Database Connection Test</h2>\n";
echo "<p>Testing database connection...</p>\n";

// Database configuration
$host = 'localhost';
$dbname = 'invoice_generator';
$username = 'invoice_user';
$password = 'invoice_password';

echo "<p><strong>Configuration:</strong></p>\n";
echo "<ul>\n";
echo "<li>Host: $host</li>\n";
echo "<li>Database: $dbname</li>\n";
echo "<li>Username: $username</li>\n";
echo "<li>Password: " . str_repeat('*', strlen($password)) . "</li>\n";
echo "</ul>\n";

try {
    echo "<p>Attempting to connect to database...</p>\n";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'><strong>✅ SUCCESS:</strong> Database connection established!</p>\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM invoices");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p style='color: green;'><strong>✅ SUCCESS:</strong> Query executed successfully!</p>\n";
    echo "<p>Number of invoices in database: <strong>" . $result['count'] . "</strong></p>\n";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ ERROR:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>\n";
    
    // Additional debugging information
    echo "<h3>Debugging Information:</h3>\n";
    echo "<ul>\n";
    echo "<li>PHP Version: " . phpversion() . "</li>\n";
    echo "<li>PDO Available: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "</li>\n";
    echo "<li>PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</li>\n";
    echo "<li>Current Working Directory: " . getcwd() . "</li>\n";
    echo "<li>Script Location: " . __FILE__ . "</li>\n";
    echo "</ul>\n";
}

echo "<hr>\n";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>\n";
?>
