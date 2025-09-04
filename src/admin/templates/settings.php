<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Invoice Generator Admin</title>
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
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-primary to-secondary text-white">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-8">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Admin Dashboard
                </h2>
                <nav class="space-y-2">
                    <a href="index.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-home mr-3"></i>Dashboard
                    </a>
                    <a href="invoices.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-file-invoice mr-3"></i>Invoices
                    </a>
                    <a href="analytics.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-chart-bar mr-3"></i>Analytics
                    </a>
                    <a href="reports.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-file-alt mr-3"></i>Reports
                    </a>
                    <a href="settings.php" class="flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-cog mr-3"></i>Settings
                    </a>
                    <a href="logout.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-cog mr-2 text-primary"></i>
                            Settings
                        </h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Welcome, Admin</span>
                            <a href="../" class="text-primary hover:text-secondary">
                                <i class="fas fa-external-link-alt mr-1"></i>View Site
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex-1 overflow-y-auto p-6">
                <!-- Settings Tabs -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6" aria-label="Tabs">
                            <button class="border-b-2 border-primary py-4 px-1 text-sm font-medium text-primary" onclick="showTab('general')">
                                <i class="fas fa-cog mr-2"></i>General
                            </button>
                            <button class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('company')">
                                <i class="fas fa-building mr-2"></i>Company
                            </button>
                            <button class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('email')">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </button>
                            <button class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('security')">
                                <i class="fas fa-shield-alt mr-2"></i>Security
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- General Settings -->
                        <div id="general-tab" class="tab-content">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">General Settings</h3>
                            <form class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                                        <input type="text" value="Invoice Generator" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="USD" selected>USD ($)</option>
                                            <option value="EUR">EUR (€)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="CAD">CAD (C$)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Tax Rate (%)</label>
                                        <input type="number" value="8.5" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number Prefix</label>
                                        <input type="text" value="INV-" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Payment Terms</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="15" selected>Net 15</option>
                                        <option value="30">Net 30</option>
                                        <option value="45">Net 45</option>
                                        <option value="60">Net 60</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <!-- Company Settings -->
                        <div id="company-tab" class="tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Company Information</h3>
                            <form class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                    <input type="text" value="Your Company Name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" value="billing@yourcompany.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                        <input type="tel" value="+1 (555) 123-4567" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">123 Business Street
Suite 100
City, State 12345</textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                                        <input type="text" value="12-3456789" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                        <input type="url" value="https://yourcompany.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Email Settings -->
                        <div id="email-tab" class="tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Email Configuration</h3>
                            <form class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                        <input type="text" value="smtp.gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                        <input type="number" value="587" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                        <input type="email" value="your-email@gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <input type="password" value="your-app-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Enable email notifications</span>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <!-- Security Settings -->
                        <div id="security-tab" class="tab-content hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6">Security Settings</h3>
                            <form class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Admin Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Admin Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Require strong passwords</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Enable two-factor authentication</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Log admin activities</span>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <!-- Save Button -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                    <i class="fas fa-save mr-2"></i>Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('nav button');
            tabButtons.forEach(button => {
                button.classList.remove('border-primary', 'text-primary');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Add active class to clicked button
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-primary', 'text-primary');
        }
    </script>
</body>
</html>
