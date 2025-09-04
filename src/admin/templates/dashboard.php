<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Invoice Generator</title>
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
                <div class="text-center mb-8">
                    <h4 class="text-xl font-bold">
                        <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
                    </h4>
                </div>
                
                <nav class="space-y-2">
                    <a class="flex items-center px-4 py-3 text-white bg-white bg-opacity-20 rounded-lg transition-colors" href="index.php">
                        <i class="fas fa-chart-pie mr-3"></i>Dashboard
                    </a>
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="invoices.php">
                        <i class="fas fa-file-invoice mr-3"></i>All Invoices
                    </a>
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="analytics.php">
                        <i class="fas fa-chart-line mr-3"></i>Analytics
                    </a>
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="reports.php">
                        <i class="fas fa-file-alt mr-3"></i>Reports
                    </a>
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="settings.php">
                        <i class="fas fa-cog mr-3"></i>Settings
                    </a>
                    <hr class="my-4 border-white border-opacity-20">
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="../index.php">
                        <i class="fas fa-external-link-alt mr-3"></i>Main App
                    </a>
                    <a class="flex items-center px-4 py-3 text-white text-opacity-80 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors" href="logout.php">
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
                        <h5 class="text-xl font-semibold text-gray-800">Admin Dashboard</h5>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">
                                <i class="fas fa-user-shield mr-1"></i>Admin
                            </span>
                            <a href="logout.php" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Messages -->
                <?php if ($message): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>" role="alert">
                        <div class="flex justify-between items-center">
                            <span><?php echo $message; ?></span>
                            <button type="button" class="ml-4 text-lg font-bold hover:opacity-75" onclick="this.parentElement.parentElement.remove()">&times;</button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-file-invoice text-white text-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-medium text-gray-500 mb-1">Total Invoices</h6>
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo number_format($analytics['total_invoices']); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-medium text-gray-500 mb-1">Total Revenue</h6>
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo formatCurrency($analytics['total_revenue']); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-medium text-gray-500 mb-1">Pending Amount</h6>
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo formatCurrency($analytics['pending_amount']); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-medium text-gray-500 mb-1">Overdue Amount</h6>
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo formatCurrency($analytics['overdue_amount']); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue Trend</h5>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                    
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Status Distribution</h5>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                
                <!-- Search and Filters -->
                <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">Search & Filter Invoices</h5>
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <input type="text" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" name="search" placeholder="Search invoices..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" name="status">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo $filters['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="sent" <?php echo $filters['status'] === 'sent' ? 'selected' : ''; ?>>Sent</option>
                            <option value="paid" <?php echo $filters['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                            <option value="overdue" <?php echo $filters['status'] === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                        </select>
                        <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" name="date_from" value="<?php echo $filters['date_from']; ?>">
                        <input type="date" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" name="date_to" value="<?php echo $filters['date_to']; ?>">
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                <i class="fas fa-search mr-1"></i>Search
                            </button>
                            <a href="index.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-times mr-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Search Results -->
                <?php if (!empty($search_results)): ?>
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-800">Search Results (<?php echo count($search_results); ?> found)</h5>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors text-sm" onclick="selectAll()">
                                <i class="fas fa-check-square mr-1"></i>Select All
                            </button>
                            <button class="px-3 py-1 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors text-sm" data-bs-toggle="modal" data-bs-target="#bulkStatusModal">
                                <i class="fas fa-sync mr-1"></i>Bulk Status
                            </button>
                            <button class="px-3 py-1 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors text-sm" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                                <i class="fas fa-trash mr-1"></i>Bulk Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($search_results as $invoice): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="invoice-checkbox" value="<?php echo $invoice['id']; ?>">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($invoice['invoice_number']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($invoice['client_name']); ?></div>
                                        <?php if ($invoice['client_email']): ?>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($invoice['client_email']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo formatCurrency($invoice['total_amount']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php 
                                            echo $invoice['status'] === 'draft' ? 'bg-gray-100 text-gray-800' : 
                                                ($invoice['status'] === 'sent' ? 'bg-blue-100 text-blue-800' : 
                                                ($invoice['status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')); 
                                        ?>">
                                            <?php echo ucfirst($invoice['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo formatDate($invoice['created_at']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="../index.php?edit=<?php echo $invoice['id']; ?>" class="text-primary hover:text-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Recent Activity and Top Clients -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h5 class="text-lg font-semibold text-gray-800">Recent Activity</h5>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php foreach (array_slice($analytics['recent_activity'], 0, 5) as $activity): ?>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['invoice_number']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($activity['client_name']); ?></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900"><?php echo formatCurrency($activity['total_amount']); ?></div>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php 
                                            echo $activity['status'] === 'draft' ? 'bg-gray-100 text-gray-800' : 
                                                ($activity['status'] === 'sent' ? 'bg-blue-100 text-blue-800' : 
                                                ($activity['status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')); 
                                        ?>">
                                            <?php echo ucfirst($activity['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h5 class="text-lg font-semibold text-gray-800">Top Clients</h5>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php foreach (array_slice($analytics['top_clients'], 0, 5) as $client): ?>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($client['client_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $client['invoice_count']; ?> invoices</div>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo formatCurrency($client['total_revenue']); ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Actions -->
                <div class="bg-white rounded-xl shadow-sm mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-800">Admin Actions</h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="export_csv">
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-download mr-2"></i>Export CSV
                                </button>
                            </form>
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                                <i class="fas fa-broom mr-2"></i>Cleanup Drafts
                            </button>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="create_backup">
                                <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Create Backup
                                </button>
                            </form>
                            <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors" data-bs-toggle="modal" data-bs-target="#systemInfoModal">
                                <i class="fas fa-info-circle mr-2"></i>System Info
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modals would go here - simplified for brevity -->
    
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = <?php echo json_encode($analytics['monthly_revenue']); ?>;
        
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => parseFloat(item.revenue)),
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
        const statusData = <?php echo json_encode($analytics['status_distribution']); ?>;
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                datasets: [{
                    data: statusData.map(item => parseInt(item.count)),
                    backgroundColor: [
                        '#6c757d',
                        '#0dcaf0',
                        '#198754',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
