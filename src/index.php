<?php
// Entry point for the Invoice Generator application
require_once 'config.php';
require_once 'functions.php';

// Initialize variables
$message = '';
$message_type = '';
$invoices = [];
$editing_invoice = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $data = sanitizeInput($_POST);
    
    switch ($action) {
        case 'create':
            $errors = validateInvoiceData($data);
            if (empty($errors)) {
                if (createInvoice($pdo, $data)) {
                    $message = 'Invoice created successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error creating invoice.';
                    $message_type = 'error';
                }
            } else {
                $message = implode('<br>', $errors);
                $message_type = 'error';
            }
            break;
            
        case 'update':
            $id = $data['id'] ?? 0;
            $errors = validateInvoiceData($data);
            if (empty($errors)) {
                if (updateInvoice($pdo, $id, $data)) {
                    $message = 'Invoice updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating invoice.';
                    $message_type = 'error';
                }
            } else {
                $message = implode('<br>', $errors);
                $message_type = 'error';
            }
            break;
            
        case 'delete':
            $id = $data['id'] ?? 0;
            if (deleteInvoice($pdo, $id)) {
                $message = 'Invoice deleted successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error deleting invoice.';
                $message_type = 'error';
            }
            break;
            
        case 'update_status':
            $id = $data['id'] ?? 0;
            $status = $data['status'] ?? '';
            if (updateInvoiceStatus($pdo, $id, $status)) {
                $message = 'Invoice status updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating invoice status.';
                $message_type = 'error';
            }
            break;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $edit_id = $_GET['edit'] ?? 0;
    if ($edit_id) {
        $editing_invoice = fetchInvoice($pdo, $edit_id);
    }
}

// Fetch invoices
$status_filter = $_GET['status'] ?? null;
$invoices = fetchInvoices($pdo, $status_filter);

// Render the main page
include 'templates/main.php';
