<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .navbar-admin {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .search-form {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-tachometer-alt me-2"></i>Admin Panel</h4>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-chart-pie me-2"></i>Dashboard
                        </a>
                        <a class="nav-link active" href="invoices.php">
                            <i class="fas fa-file-invoice me-2"></i>All Invoices
                        </a>
                        <a class="nav-link" href="analytics.php">
                            <i class="fas fa-chart-line me-2"></i>Analytics
                        </a>
                        <a class="nav-link" href="reports.php">
                            <i class="fas fa-file-alt me-2"></i>Reports
                        </a>
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-external-link-alt me-2"></i>Main App
                        </a>
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navigation -->
                    <nav class="navbar navbar-admin">
                        <div class="container-fluid">
                            <h5 class="mb-0">Invoice Management</h5>
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">
                                    <i class="fas fa-user-shield me-1"></i>Admin
                                </span>
                                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <div class="p-4">
                        <!-- Messages -->
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Search and Filters -->
                        <div class="search-form">
                            <h5 class="mb-3">Search & Filter Invoices</h5>
                            <form method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="search" placeholder="Search invoices..." value="<?php echo htmlspecialchars($search_term); ?>">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="draft" <?php echo $filters['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                        <option value="sent" <?php echo $filters['status'] === 'sent' ? 'selected' : ''; ?>>Sent</option>
                                        <option value="paid" <?php echo $filters['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="overdue" <?php echo $filters['status'] === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" value="<?php echo $filters['date_from']; ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" value="<?php echo $filters['date_to']; ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>Search
                                    </button>
                                    <a href="invoices.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Invoices Table -->
                        <div class="table-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>All Invoices (<?php echo $total_invoices; ?> total)</h5>
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                        <i class="fas fa-check-square me-1"></i>Select All
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#bulkStatusModal">
                                        <i class="fas fa-sync me-1"></i>Bulk Status
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                                        <i class="fas fa-trash me-1"></i>Bulk Delete
                                    </button>
                                </div>
                            </div>
                            
                            <?php if (empty($invoices)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No invoices found</h5>
                                    <p class="text-muted">Try adjusting your search criteria.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="selectAllCheckbox" onchange="toggleAll()"></th>
                                                <th>Invoice #</th>
                                                <th>Client</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Issue Date</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoices as $invoice): ?>
                                            <tr>
                                                <td><input type="checkbox" class="invoice-checkbox" value="<?php echo $invoice['id']; ?>"></td>
                                                <td><strong><?php echo htmlspecialchars($invoice['invoice_number']); ?></strong></td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($invoice['client_name']); ?></strong>
                                                        <?php if ($invoice['client_email']): ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($invoice['client_email']); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong><?php echo formatCurrency($invoice['total_amount']); ?></strong>
                                                    <?php if ($invoice['tax_amount'] > 0): ?>
                                                        <br><small class="text-muted">Tax: <?php echo formatCurrency($invoice['tax_amount']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo $invoice['status'] === 'draft' ? 'secondary' : 
                                                            ($invoice['status'] === 'sent' ? 'info' : 
                                                            ($invoice['status'] === 'paid' ? 'success' : 'danger')); 
                                                    ?>">
                                                        <?php echo ucfirst($invoice['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo formatDate($invoice['issue_date']); ?></td>
                                                <td>
                                                    <?php echo formatDate($invoice['due_date']); ?>
                                                    <?php if (strtotime($invoice['due_date']) < time() && $invoice['status'] !== 'paid'): ?>
                                                        <br><small class="text-danger">Overdue</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="../index.php?edit=<?php echo $invoice['id']; ?>" class="btn btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-outline-info" onclick="updateStatus(<?php echo $invoice['id']; ?>)" title="Update Status">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="deleteInvoice(<?php echo $invoice['id']; ?>)" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <?php if ($total_pages > 1): ?>
                                <nav aria-label="Invoice pagination">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($filters['status']); ?>&date_from=<?php echo urlencode($filters['date_from']); ?>&date_to=<?php echo urlencode($filters['date_to']); ?>">Previous</a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($filters['status']); ?>&date_from=<?php echo urlencode($filters['date_from']); ?>&date_to=<?php echo urlencode($filters['date_to']); ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($filters['status']); ?>&date_from=<?php echo urlencode($filters['date_from']); ?>&date_to=<?php echo urlencode($filters['date_to']); ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulk Status Update Modal -->
    <div class="modal fade" id="bulkStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="bulk_status_update">
                        <div class="mb-3">
                            <label for="new_status" class="form-label">New Status</label>
                            <select class="form-select" name="new_status" required>
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div id="selectedInvoices"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bulk Delete Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="bulk_delete">
                        <p>Are you sure you want to delete the selected invoices? This action cannot be undone.</p>
                        <div id="selectedInvoicesDelete"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Invoices</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bulk operations
        function selectAll() {
            const checkboxes = document.querySelectorAll('.invoice-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = true);
            updateSelectedInvoices();
        }
        
        function toggleAll() {
            const masterCheckbox = document.getElementById('selectAllCheckbox');
            const checkboxes = document.querySelectorAll('.invoice-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = masterCheckbox.checked);
            updateSelectedInvoices();
        }
        
        function updateSelectedInvoices() {
            const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            
            // Update hidden inputs for bulk operations
            const statusForm = document.querySelector('#bulkStatusModal form');
            const deleteForm = document.querySelector('#bulkDeleteModal form');
            
            // Remove existing hidden inputs
            statusForm.querySelectorAll('input[name="invoice_ids[]"]').forEach(input => input.remove());
            deleteForm.querySelectorAll('input[name="invoice_ids[]"]').forEach(input => input.remove());
            
            // Add new hidden inputs
            selectedIds.forEach(id => {
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'invoice_ids[]';
                statusInput.value = id;
                statusForm.appendChild(statusInput);
                
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'invoice_ids[]';
                deleteInput.value = id;
                deleteForm.appendChild(deleteInput);
            });
        }
        
        // Add event listeners to checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.invoice-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedInvoices);
            });
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
