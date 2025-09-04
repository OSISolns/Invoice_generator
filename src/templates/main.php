<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generator</title>
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
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-primary to-secondary shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="flex items-center text-white font-bold text-xl">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    Invoice Generator
                </a>
                <div class="flex space-x-4">
                    <a href="#" onclick="showCreateForm()" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>New Invoice
                    </a>
                    <a href="admin/" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-tachometer-alt mr-1"></i>Admin Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>" role="alert">
                <div class="flex justify-between items-center">
                    <span><?php echo $message; ?></span>
                    <button type="button" class="ml-4 text-lg font-bold hover:opacity-75" onclick="this.parentElement.parentElement.remove()">&times;</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Totals Summary -->
        <?php 
        $totals = calculateTotals($invoices);
        if ($totals['count'] > 0): 
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white shadow-lg">
                <div class="text-center">
                    <h5 class="text-sm font-medium opacity-90 mb-2">Total Invoices</h5>
                    <h2 class="text-3xl font-bold"><?php echo $totals['count']; ?></h2>
                </div>
            </div>
            <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white shadow-lg">
                <div class="text-center">
                    <h5 class="text-sm font-medium opacity-90 mb-2">Gross Amount</h5>
                    <h2 class="text-3xl font-bold"><?php echo formatCurrency($totals['total_gross']); ?></h2>
                </div>
            </div>
            <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white shadow-lg">
                <div class="text-center">
                    <h5 class="text-sm font-medium opacity-90 mb-2">Total Tax</h5>
                    <h2 class="text-3xl font-bold"><?php echo formatCurrency($totals['total_tax']); ?></h2>
                </div>
            </div>
            <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white shadow-lg">
                <div class="text-center">
                    <h5 class="text-sm font-medium opacity-90 mb-2">Net Total</h5>
                    <h2 class="text-3xl font-bold"><?php echo formatCurrency($totals['total_amount']); ?></h2>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="?" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo !$status_filter ? 'bg-primary text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?>">All</a>
                <a href="?status=draft" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $status_filter === 'draft' ? 'bg-gray-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?>">Draft</a>
                <a href="?status=sent" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $status_filter === 'sent' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?>">Sent</a>
                <a href="?status=paid" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $status_filter === 'paid' ? 'bg-green-500 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?>">Paid</a>
                <a href="?status=overdue" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $status_filter === 'overdue' ? 'bg-red-500 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?>">Overdue</a>
            </div>
        </div>

        <!-- Invoice Form -->
        <div id="invoiceForm" class="bg-gray-100 rounded-xl p-6 mb-8 shadow-sm" style="<?php echo $editing_invoice ? '' : 'display: none;'; ?>">
            <h4 class="text-xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-<?php echo $editing_invoice ? 'edit' : 'plus'; ?> mr-2"></i><?php echo $editing_invoice ? 'Edit Invoice' : 'Create New Invoice'; ?>
            </h4>
            <form method="POST" id="invoiceFormElement">
                <input type="hidden" name="action" value="<?php echo $editing_invoice ? 'update' : 'create'; ?>">
                <?php if ($editing_invoice): ?>
                    <input type="hidden" name="id" value="<?php echo $editing_invoice['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="client_name" name="client_name" 
                               value="<?php echo $editing_invoice['client_name'] ?? ''; ?>" required>
                    </div>
                    <div>
                        <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Client Email</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="client_email" name="client_email" 
                               value="<?php echo $editing_invoice['client_email'] ?? ''; ?>">
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="client_address" class="block text-sm font-medium text-gray-700 mb-2">Client Address</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="client_address" name="client_address" rows="2"><?php echo $editing_invoice['client_address'] ?? ''; ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="amount" name="amount" 
                                   value="<?php echo $editing_invoice['amount'] ?? ''; ?>" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                        <div class="relative">
                            <input type="number" class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="tax_rate" name="tax_rate" 
                                   value="<?php echo $editing_invoice['tax_rate'] ?? '0'; ?>" step="0.01" min="0" max="100">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="status" name="status">
                            <option value="draft" <?php echo ($editing_invoice['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="sent" <?php echo ($editing_invoice['status'] ?? '') === 'sent' ? 'selected' : ''; ?>>Sent</option>
                            <option value="paid" <?php echo ($editing_invoice['status'] ?? '') === 'paid' ? 'selected' : ''; ?>>Paid</option>
                            <option value="overdue" <?php echo ($editing_invoice['status'] ?? '') === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="issue_date" name="issue_date" 
                               value="<?php echo $editing_invoice['issue_date'] ?? date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="due_date" name="due_date" 
                               value="<?php echo $editing_invoice['due_date'] ?? ''; ?>" required>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="description" name="description" rows="3"><?php echo $editing_invoice['description'] ?? ''; ?></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors" onclick="hideForm()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                        <i class="fas fa-save mr-1"></i><?php echo $editing_invoice ? 'Update Invoice' : 'Create Invoice'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Invoices List -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Invoices
                </h5>
                <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors text-sm" onclick="showCreateForm()">
                    <i class="fas fa-plus mr-1"></i>New Invoice
                </button>
            </div>
            <div class="p-0">
                <?php if (empty($invoices)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-6xl text-gray-400 mb-4"></i>
                        <h5 class="text-gray-500 text-lg font-medium mb-2">No invoices found</h5>
                        <p class="text-gray-400 mb-4">Create your first invoice to get started.</p>
                        <button class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors" onclick="showCreateForm()">
                            <i class="fas fa-plus mr-1"></i>Create Invoice
                        </button>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($invoices as $invoice): ?>
                                <tr class="hover:bg-gray-50">
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
                                        <?php if ($invoice['tax_amount'] > 0): ?>
                                            <div class="text-sm text-gray-500">Tax: <?php echo formatCurrency($invoice['tax_amount']); ?></div>
                                        <?php endif; ?>
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
                                        <div class="text-sm text-gray-900"><?php echo formatDate($invoice['due_date']); ?></div>
                                        <?php if (strtotime($invoice['due_date']) < time() && $invoice['status'] !== 'paid'): ?>
                                            <div class="text-sm text-red-600">Overdue</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="?edit=<?php echo $invoice['id']; ?>" class="text-primary hover:text-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="text-blue-600 hover:text-blue-800" onclick="updateStatus(<?php echo $invoice['id']; ?>)" title="Update Status">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-800" onclick="deleteInvoice(<?php echo $invoice['id']; ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Update Invoice Status</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('statusModal')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="POST" id="statusForm">
                    <div class="mb-4">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" id="statusInvoiceId">
                        <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" id="newStatus" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors" onclick="closeModal('statusModal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('deleteModal')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="POST" id="deleteForm">
                    <div class="mb-4">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteInvoiceId">
                        <p class="text-gray-600">Are you sure you want to delete this invoice? This action cannot be undone.</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors" onclick="closeModal('deleteModal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Delete Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCreateForm() {
            document.getElementById('invoiceForm').style.display = 'block';
            document.getElementById('invoiceForm').scrollIntoView({ behavior: 'smooth' });
            if (document.querySelector('input[name="id"]')) {
                window.location.href = '?';
            }
        }

        function hideForm() {
            document.getElementById('invoiceForm').style.display = 'none';
            if (document.querySelector('input[name="id"]')) {
                window.location.href = '?';
            }
        }

        function updateStatus(invoiceId) {
            document.getElementById('statusInvoiceId').value = invoiceId;
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function deleteInvoice(invoiceId) {
            document.getElementById('deleteInvoiceId').value = invoiceId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('bg-opacity-50')) {
                event.target.classList.add('hidden');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);

        // Calculate tax amount in real-time
        document.getElementById('amount').addEventListener('input', calculateTax);
        document.getElementById('tax_rate').addEventListener('input', calculateTax);

        function calculateTax() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const taxAmount = amount * (taxRate / 100);
            const totalAmount = amount + taxAmount;
            console.log('Amount:', amount, 'Tax:', taxAmount, 'Total:', totalAmount);
        }
    </script>
</body>
</html>
