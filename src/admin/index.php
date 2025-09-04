<?php
// Admin Dashboard Entry Point
require_once '../config.php';
require_once '../functions.php';
require_once '../admin_functions.php';

// Check admin authentication
if (!checkAdminAuth()) {
    header('Location: login.php');
    exit;
}

// Get analytics data
$analytics = getAnalyticsData($pdo);
$system_stats = getSystemStats($pdo);
$performance = getPerformanceMetrics($pdo);

// Handle admin actions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'export_csv':
            exportInvoicesToCSV($pdo, $_POST);
            break;
            
        case 'bulk_status_update':
            $invoice_ids = $_POST['invoice_ids'] ?? [];
            $new_status = $_POST['new_status'] ?? '';
            if (bulkUpdateStatus($pdo, $invoice_ids, $new_status)) {
                $message = 'Bulk status update completed successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating invoice statuses.';
                $message_type = 'error';
            }
            break;
            
        case 'bulk_delete':
            $invoice_ids = $_POST['invoice_ids'] ?? [];
            if (bulkDeleteInvoices($pdo, $invoice_ids)) {
                $message = 'Selected invoices deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting invoices.';
                $message_type = 'error';
            }
            break;
            
        case 'cleanup_drafts':
            $days = $_POST['days'] ?? 30;
            if (cleanupOldDrafts($pdo, $days)) {
                $message = 'Old draft invoices cleaned up successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error cleaning up draft invoices.';
                $message_type = 'error';
            }
            break;
            
        case 'create_backup':
            $backup_file = createDatabaseBackup($pdo);
            $message = "Database backup created: $backup_file";
            $message_type = 'success';
            break;
    }
}

// Get search results if searching
$search_results = [];
$search_term = $_GET['search'] ?? '';
$filters = [
    'status' => $_GET['status'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
    'amount_min' => $_GET['amount_min'] ?? '',
    'amount_max' => $_GET['amount_max'] ?? ''
];

if (!empty($search_term) || !empty(array_filter($filters))) {
    $search_results = searchInvoices($pdo, $search_term, $filters);
}

include 'templates/dashboard.php';
?>
