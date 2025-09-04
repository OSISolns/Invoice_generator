<?php
// Admin Invoices Management Page
require_once '../config.php';
require_once '../functions.php';
require_once '../admin_functions.php';

// Check admin authentication
if (!checkAdminAuth()) {
    header('Location: login.php');
    exit;
}

// Handle actions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
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
    }
}

// Get all invoices with pagination
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$search_term = $_GET['search'] ?? '';
$filters = [
    'status' => $_GET['status'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? ''
];

$invoices = searchInvoices($pdo, $search_term, $filters);
$total_invoices = count($invoices);
$invoices = array_slice($invoices, $offset, $limit);
$total_pages = ceil($total_invoices / $limit);

include 'templates/invoices.php';
?>
