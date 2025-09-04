<?php
// Admin Login Page
require_once '../admin_functions.php';

// Redirect if already logged in
if (checkAdminAuth()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    if (adminLogin($password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Invoice Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
<body class="min-h-screen bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <i class="fas fa-shield-alt text-5xl text-primary mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800">Admin Access</h3>
            <p class="text-gray-600 mt-2">Enter your admin password to continue</p>
        </div>
        
        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span><?php echo $error; ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent" id="password" name="password" required>
                </div>
            </div>
            
            <div class="w-full">
                <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 px-6 rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200 uppercase tracking-wide">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </div>
        </form>
        
        <div class="text-center mt-6">
            <a href="../index.php" class="text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i>Back to Main App
            </a>
        </div>
        
        <div class="text-center mt-6">
            <small class="text-gray-500">
                Default password: <code class="bg-gray-100 px-2 py-1 rounded">admin123</code><br>
                <em class="text-sm">Change this in production!</em>
            </small>
        </div>
    </div>
</body>
</html>
