<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Invoice Generator Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="analytics.php" class="flex items-center px-4 py-2 bg-white bg-opacity-20 rounded-lg">
                        <i class="fas fa-chart-bar mr-3"></i>Analytics
                    </a>
                    <a href="reports.php" class="flex items-center px-4 py-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
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
                            <i class="fas fa-chart-bar mr-2 text-primary"></i>
                            Analytics Dashboard
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
                <!-- Analytics Content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Revenue Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-line mr-2 text-primary"></i>
                            Revenue Trends
                        </h3>
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Invoice Status Distribution -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-primary"></i>
                            Invoice Status Distribution
                        </h3>
                        <canvas id="statusChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Monthly Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
                        <div class="text-center">
                            <h5 class="text-sm font-medium opacity-90 mb-2">This Month</h5>
                            <h2 class="text-3xl font-bold">$12,450</h2>
                            <p class="text-sm opacity-75">+15% from last month</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                        <div class="text-center">
                            <h5 class="text-sm font-medium opacity-90 mb-2">Paid Invoices</h5>
                            <h2 class="text-3xl font-bold">24</h2>
                            <p class="text-sm opacity-75">85% success rate</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
                        <div class="text-center">
                            <h5 class="text-sm font-medium opacity-90 mb-2">Pending</h5>
                            <h2 class="text-3xl font-bold">8</h2>
                            <p class="text-sm opacity-75">3 overdue</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white">
                        <div class="text-center">
                            <h5 class="text-sm font-medium opacity-90 mb-2">Overdue</h5>
                            <h2 class="text-3xl font-bold">3</h2>
                            <p class="text-sm opacity-75">$2,340 total</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-history mr-2 text-primary"></i>
                        Recent Activity
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Invoice INV-001 marked as paid</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-plus text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">New invoice INV-004 created</p>
                                <p class="text-xs text-gray-500">4 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-exclamation text-yellow-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Invoice INV-003 is now overdue</p>
                                <p class="text-xs text-gray-500">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Sent', 'Draft', 'Overdue'],
                datasets: [{
                    data: [45, 25, 15, 15],
                    backgroundColor: [
                        '#10B981',
                        '#3B82F6',
                        '#6B7280',
                        '#EF4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
