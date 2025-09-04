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
                    <a href="reports.php" class="flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-file-alt mr-3"></i>Reports
                    </a>
                    <a href="settings.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
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
                            <i class="fas fa-file-alt mr-2 text-primary"></i>
                            Reports
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
                <!-- Report Filters -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-filter mr-2 text-primary"></i>
                        Generate Report
                    </h3>
                    <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="revenue">Revenue Report</option>
                                <option value="invoices">Invoice Summary</option>
                                <option value="clients">Client Report</option>
                                <option value="overdue">Overdue Invoices</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                <i class="fas fa-download mr-2"></i>Generate
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Reports -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Monthly Revenue</h4>
                            <i class="fas fa-chart-line text-2xl text-primary"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-2">$24,580</p>
                        <p class="text-sm text-gray-600">+12% from last month</p>
                        <button class="mt-4 w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Client Summary</h4>
                            <i class="fas fa-users text-2xl text-primary"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-800 mb-2">15</p>
                        <p class="text-sm text-gray-600">Active clients</p>
                        <button class="mt-4 w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Overdue Report</h4>
                            <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                        </div>
                        <p class="text-3xl font-bold text-red-600 mb-2">3</p>
                        <p class="text-sm text-gray-600">Overdue invoices</p>
                        <button class="mt-4 w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>
                </div>

                <!-- Report History -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-history mr-2 text-primary"></i>
                        Recent Reports
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Monthly Revenue Report</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Revenue</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 hours ago</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-primary hover:text-secondary mr-3">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Client Summary Report</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Client</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 day ago</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-primary hover:text-secondary mr-3">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Overdue Invoices Report</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Overdue</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 days ago</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-primary hover:text-secondary mr-3">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
