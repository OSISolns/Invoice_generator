<?php
/**
 * Invoice Generator Installation Script
 * Run this script to set up the database and verify installation
 */

// Database configuration
$host = 'localhost';
$dbname = 'invoice_generator';
$username = 'invoice_user';
$password = 'invoice_password';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Invoice Generator Installation</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#667eea',
                        secondary: '#764ba2',
                    }
                }
            }
        }
    </script>
</head>
<body class='bg-gray-50 min-h-screen'>
    <div class='max-w-4xl mx-auto p-8'>
        <div class='bg-white rounded-xl shadow-lg p-8'>
            <div class='text-center mb-8'>
                <i class='fas fa-cogs text-5xl text-primary mb-4'></i>
                <h1 class='text-3xl font-bold text-gray-800'>Invoice Generator Installation</h1>
                <p class='text-gray-600 mt-2'>Setting up your invoice management system</p>
            </div>\n";

// Test database connection
echo "<div class='mb-6'>
        <h2 class='text-xl font-semibold text-gray-800 mb-3 flex items-center'>
            <span class='w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold'>1</span>
            Testing Database Connection
        </h2>\n";
try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='flex items-center p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg'>
            <i class='fas fa-check-circle mr-2'></i>
            <span>Database connection successful</span>
          </div>\n";
} catch (PDOException $e) {
    echo "<div class='flex items-center p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg mb-2'>
            <i class='fas fa-times-circle mr-2'></i>
            <span>Database connection failed: " . $e->getMessage() . "</span>
          </div>
          <div class='flex items-center p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg'>
            <i class='fas fa-info-circle mr-2'></i>
            <span>Please check your database credentials in config.php</span>
          </div>\n";
    echo "</div></div></div></body></html>";
    exit;
}
echo "</div>\n";

// Create database if it doesn't exist
echo "<div class='mb-6'>
        <h2 class='text-xl font-semibold text-gray-800 mb-3 flex items-center'>
            <span class='w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold'>2</span>
            Creating Database
        </h2>\n";
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<div class='flex items-center p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg'>
            <i class='fas fa-check-circle mr-2'></i>
            <span>Database '$dbname' created/verified</span>
          </div>\n";
} catch (PDOException $e) {
    echo "<div class='flex items-center p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg'>
            <i class='fas fa-times-circle mr-2'></i>
            <span>Failed to create database: " . $e->getMessage() . "</span>
          </div>\n";
    echo "</div></div></div></body></html>";
    exit;
}
echo "</div>\n";

// Connect to the specific database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p class='error'>✗ Failed to connect to database: " . $e->getMessage() . "</p>\n";
    exit;
}

// Create tables
echo "<h2>3. Creating Tables</h2>\n";
$schema = "
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_email VARCHAR(255),
    client_address TEXT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    tax_rate DECIMAL(5, 2) DEFAULT 0.00,
    tax_amount DECIMAL(10, 2) DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    due_date DATE NOT NULL,
    issue_date DATE NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue') DEFAULT 'draft',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
";

try {
    $pdo->exec($schema);
    echo "<p class='success'>✓ Invoices table created/verified</p>\n";
} catch (PDOException $e) {
    echo "<p class='error'>✗ Failed to create table: " . $e->getMessage() . "</p>\n";
    exit;
}

// Insert sample data
echo "<h2>4. Inserting Sample Data</h2>\n";
$sampleData = [
    [
        'Acme Corporation',
        'billing@acme.com',
        '123 Business St, City, State 12345',
        'INV-001',
        1500.00,
        8.5,
        127.50,
        1627.50,
        '2024-02-15',
        '2024-01-15',
        'sent',
        'Website development services'
    ],
    [
        'Tech Solutions Inc',
        'accounts@techsolutions.com',
        '456 Tech Ave, Tech City, TC 67890',
        'INV-002',
        2500.00,
        8.5,
        212.50,
        2712.50,
        '2024-02-20',
        '2024-01-20',
        'paid',
        'Software consulting'
    ],
    [
        'Design Studio',
        'hello@designstudio.com',
        '789 Creative Blvd, Art District, AD 11111',
        'INV-003',
        800.00,
        8.5,
        68.00,
        868.00,
        '2024-02-10',
        '2024-01-10',
        'overdue',
        'Logo design and branding'
    ]
];

$stmt = $pdo->prepare("
    INSERT IGNORE INTO invoices (
        client_name, client_email, client_address, invoice_number, 
        amount, tax_rate, tax_amount, total_amount, 
        due_date, issue_date, status, description
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$inserted = 0;
foreach ($sampleData as $data) {
    try {
        $stmt->execute($data);
        $inserted++;
    } catch (PDOException $e) {
        // Ignore duplicate key errors
    }
}

echo "<p class='success'>✓ Sample data inserted ($inserted records)</p>\n";

// Test application files
echo "<h2>5. Verifying Application Files</h2>\n";
$requiredFiles = [
    'src/config.php',
    'src/functions.php',
    'src/index.php',
    'src/templates/main.php'
];

$allFilesExist = true;
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "<p class='success'>✓ $file exists</p>\n";
    } else {
        echo "<p class='error'>✗ $file missing</p>\n";
        $allFilesExist = false;
    }
}

// Final status
echo "<div class='mb-6'>
        <h2 class='text-xl font-semibold text-gray-800 mb-3 flex items-center'>
            <span class='w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold'>6</span>
            Installation Complete
        </h2>\n";
if ($allFilesExist) {
    echo "<div class='p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg mb-4'>
            <div class='flex items-center'>
                <i class='fas fa-check-circle mr-2'></i>
                <span class='font-semibold'>Installation successful!</span>
            </div>
          </div>
          <div class='space-y-2 text-sm'>
            <div class='flex items-center p-2 bg-blue-50 rounded-lg'>
                <i class='fas fa-link mr-2 text-blue-600'></i>
                <span>You can now access the Invoice Generator at: <a href='src/' class='text-primary hover:underline font-medium'>src/</a></span>
            </div>
            <div class='flex items-center p-2 bg-blue-50 rounded-lg'>
                <i class='fas fa-file-invoice mr-2 text-blue-600'></i>
                <span>Sample invoices have been created for testing.</span>
            </div>
          </div>\n";
} else {
    echo "<div class='flex items-center p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg'>
            <i class='fas fa-times-circle mr-2'></i>
            <span>Installation incomplete. Please check missing files.</span>
          </div>\n";
}

echo "</div>
      <div class='bg-gray-50 rounded-lg p-6'>
        <h3 class='text-lg font-semibold text-gray-800 mb-4 flex items-center'>
            <i class='fas fa-list-check mr-2 text-primary'></i>Next Steps
        </h3>
        <ul class='space-y-2 text-sm text-gray-600'>
            <li class='flex items-center'>
                <i class='fas fa-arrow-right mr-2 text-primary'></i>
                Update database credentials in src/config.php if needed
            </li>
            <li class='flex items-center'>
                <i class='fas fa-arrow-right mr-2 text-primary'></i>
                Access the application at src/index.php
            </li>
            <li class='flex items-center'>
                <i class='fas fa-arrow-right mr-2 text-primary'></i>
                Delete this install.php file for security
            </li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>";
?>
