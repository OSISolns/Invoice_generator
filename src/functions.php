<?php
// Utility functions for the Invoice Generator application

// Generate unique invoice number
function generateInvoiceNumber($pdo) {
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM invoices');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['count'] + 1;
    return 'INV-' . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Fetch all invoices with optional filtering
function fetchInvoices($pdo, $status = null) {
    if ($status) {
        $stmt = $pdo->prepare('SELECT * FROM invoices WHERE status = ? ORDER BY created_at DESC');
        $stmt->execute([$status]);
    } else {
        $stmt = $pdo->query('SELECT * FROM invoices ORDER BY created_at DESC');
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single invoice by ID
function fetchInvoice($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Create new invoice
function createInvoice($pdo, $data) {
    $invoice_number = generateInvoiceNumber($pdo);
    $tax_amount = $data['amount'] * ($data['tax_rate'] / 100);
    $total_amount = $data['amount'] + $tax_amount;
    
    $stmt = $pdo->prepare('
        INSERT INTO invoices (
            client_name, client_email, client_address, invoice_number, 
            amount, tax_rate, tax_amount, total_amount, 
            due_date, issue_date, status, description
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    
    return $stmt->execute([
        $data['client_name'],
        $data['client_email'],
        $data['client_address'],
        $invoice_number,
        $data['amount'],
        $data['tax_rate'],
        $data['due_date'],
        $data['issue_date'],
        $data['status'],
        $data['description'],
        $tax_amount,
        $total_amount
    ]);
}

// Update existing invoice
function updateInvoice($pdo, $id, $data) {
    $tax_amount = $data['amount'] * ($data['tax_rate'] / 100);
    $total_amount = $data['amount'] + $tax_amount;
    
    $stmt = $pdo->prepare('
        UPDATE invoices SET 
            client_name = ?, client_email = ?, client_address = ?, 
            amount = ?, tax_rate = ?, tax_amount = ?, total_amount = ?, 
            due_date = ?, issue_date = ?, status = ?, description = ?
        WHERE id = ?
    ');
    
    return $stmt->execute([
        $data['client_name'],
        $data['client_email'],
        $data['client_address'],
        $data['amount'],
        $data['tax_rate'],
        $tax_amount,
        $total_amount,
        $data['due_date'],
        $data['issue_date'],
        $data['status'],
        $data['description'],
        $id
    ]);
}

// Delete invoice
function deleteInvoice($pdo, $id) {
    $stmt = $pdo->prepare('DELETE FROM invoices WHERE id = ?');
    return $stmt->execute([$id]);
}

// Update invoice status
function updateInvoiceStatus($pdo, $id, $status) {
    $stmt = $pdo->prepare('UPDATE invoices SET status = ? WHERE id = ?');
    return $stmt->execute([$status, $id]);
}

// Input validation and sanitization
function validateInvoiceData($data) {
    $errors = [];
    
    // Required fields
    if (empty($data['client_name'])) {
        $errors[] = 'Client name is required';
    }
    if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
        $errors[] = 'Valid amount is required';
    }
    if (empty($data['due_date'])) {
        $errors[] = 'Due date is required';
    }
    if (empty($data['issue_date'])) {
        $errors[] = 'Issue date is required';
    }
    
    // Email validation
    if (!empty($data['client_email']) && !filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Date validation
    if (!empty($data['due_date']) && !strtotime($data['due_date'])) {
        $errors[] = 'Invalid due date format';
    }
    if (!empty($data['issue_date']) && !strtotime($data['issue_date'])) {
        $errors[] = 'Invalid issue date format';
    }
    
    // Tax rate validation
    if (!empty($data['tax_rate']) && (!is_numeric($data['tax_rate']) || $data['tax_rate'] < 0 || $data['tax_rate'] > 100)) {
        $errors[] = 'Tax rate must be between 0 and 100';
    }
    
    return $errors;
}

// Sanitize input data
function sanitizeInput($data) {
    $sanitized = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}

// Format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

// Format date
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

// Get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'draft':
            return 'badge-secondary';
        case 'sent':
            return 'badge-info';
        case 'paid':
            return 'badge-success';
        case 'overdue':
            return 'badge-danger';
        default:
            return 'badge-secondary';
    }
}

// Calculate totals
function calculateTotals($invoices) {
    $totals = [
        'total_amount' => 0,
        'total_tax' => 0,
        'total_gross' => 0,
        'count' => count($invoices)
    ];
    
    foreach ($invoices as $invoice) {
        $totals['total_amount'] += $invoice['total_amount'];
        $totals['total_tax'] += $invoice['tax_amount'];
        $totals['total_gross'] += $invoice['amount'];
    }
    
    return $totals;
}
