<?php
// Admin-specific functions for the Invoice Generator application

// Get comprehensive analytics data
function getAnalyticsData($pdo) {
    $analytics = [];
    
    // Total invoices count
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM invoices');
    $analytics['total_invoices'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total revenue
    $stmt = $pdo->query('SELECT SUM(total_amount) as total FROM invoices WHERE status = "paid"');
    $analytics['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    // Pending amount
    $stmt = $pdo->query('SELECT SUM(total_amount) as total FROM invoices WHERE status IN ("sent", "overdue")');
    $analytics['pending_amount'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    // Overdue amount
    $stmt = $pdo->query('SELECT SUM(total_amount) as total FROM invoices WHERE status = "overdue"');
    $analytics['overdue_amount'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    // Monthly revenue (last 12 months)
    $stmt = $pdo->query('
        SELECT 
            DATE_FORMAT(created_at, "%Y-%m") as month,
            SUM(total_amount) as revenue
        FROM invoices 
        WHERE status = "paid" 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, "%Y-%m")
        ORDER BY month ASC
    ');
    $analytics['monthly_revenue'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Status distribution
    $stmt = $pdo->query('
        SELECT status, COUNT(*) as count, SUM(total_amount) as amount
        FROM invoices 
        GROUP BY status
    ');
    $analytics['status_distribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Top clients by revenue
    $stmt = $pdo->query('
        SELECT 
            client_name,
            COUNT(*) as invoice_count,
            SUM(total_amount) as total_revenue
        FROM invoices 
        WHERE status = "paid"
        GROUP BY client_name
        ORDER BY total_revenue DESC
        LIMIT 10
    ');
    $analytics['top_clients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recent activity
    $stmt = $pdo->query('
        SELECT 
            invoice_number,
            client_name,
            total_amount,
            status,
            created_at
        FROM invoices 
        ORDER BY created_at DESC
        LIMIT 10
    ');
    $analytics['recent_activity'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Average invoice value
    $stmt = $pdo->query('SELECT AVG(total_amount) as avg_value FROM invoices');
    $analytics['avg_invoice_value'] = $stmt->fetch(PDO::FETCH_ASSOC)['avg_value'] ?? 0;
    
    // Payment trends (last 30 days)
    $stmt = $pdo->query('
        SELECT 
            DATE(updated_at) as date,
            COUNT(*) as paid_count,
            SUM(total_amount) as daily_revenue
        FROM invoices 
        WHERE status = "paid" 
        AND updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(updated_at)
        ORDER BY date ASC
    ');
    $analytics['payment_trends'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $analytics;
}

// Get system statistics
function getSystemStats($pdo) {
    $stats = [];
    
    // Database size
    $stmt = $pdo->query('
        SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
        FROM information_schema.tables 
        WHERE table_schema = DATABASE()
    ');
    $stats['db_size'] = $stmt->fetch(PDO::FETCH_ASSOC)['size_mb'] ?? 0;
    
    // Table statistics
    $tables = ['invoices'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $stats['table_counts'][$table] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    // PHP and server info
    $stats['php_version'] = PHP_VERSION;
    $stats['server_software'] = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
    $stats['mysql_version'] = $pdo->query('SELECT VERSION() as version')->fetch(PDO::FETCH_ASSOC)['version'];
    
    return $stats;
}

// Export data functions
function exportInvoicesToCSV($pdo, $filters = []) {
    $where = '';
    $params = [];
    
    if (!empty($filters['status'])) {
        $where = 'WHERE status = ?';
        $params[] = $filters['status'];
    }
    
    if (!empty($filters['date_from'])) {
        $where .= $where ? ' AND ' : 'WHERE ';
        $where .= 'created_at >= ?';
        $params[] = $filters['date_from'];
    }
    
    if (!empty($filters['date_to'])) {
        $where .= $where ? ' AND ' : 'WHERE ';
        $where .= 'created_at <= ?';
        $params[] = $filters['date_to'];
    }
    
    $stmt = $pdo->prepare("SELECT * FROM invoices $where ORDER BY created_at DESC");
    $stmt->execute($params);
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $filename = 'invoices_export_' . date('Y-m-d_H-i-s') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, [
        'ID', 'Invoice Number', 'Client Name', 'Client Email', 'Client Address',
        'Amount', 'Tax Rate', 'Tax Amount', 'Total Amount',
        'Issue Date', 'Due Date', 'Status', 'Description', 'Created At'
    ]);
    
    // CSV data
    foreach ($invoices as $invoice) {
        fputcsv($output, [
            $invoice['id'],
            $invoice['invoice_number'],
            $invoice['client_name'],
            $invoice['client_email'],
            $invoice['client_address'],
            $invoice['amount'],
            $invoice['tax_rate'],
            $invoice['tax_amount'],
            $invoice['total_amount'],
            $invoice['issue_date'],
            $invoice['due_date'],
            $invoice['status'],
            $invoice['description'],
            $invoice['created_at']
        ]);
    }
    
    fclose($output);
    exit;
}

// Bulk operations
function bulkUpdateStatus($pdo, $invoice_ids, $new_status) {
    if (empty($invoice_ids) || empty($new_status)) {
        return false;
    }
    
    $placeholders = str_repeat('?,', count($invoice_ids) - 1) . '?';
    $stmt = $pdo->prepare("UPDATE invoices SET status = ? WHERE id IN ($placeholders)");
    
    $params = array_merge([$new_status], $invoice_ids);
    return $stmt->execute($params);
}

function bulkDeleteInvoices($pdo, $invoice_ids) {
    if (empty($invoice_ids)) {
        return false;
    }
    
    $placeholders = str_repeat('?,', count($invoice_ids) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM invoices WHERE id IN ($placeholders)");
    
    return $stmt->execute($invoice_ids);
}

// Search and filter functions
function searchInvoices($pdo, $search_term, $filters = []) {
    $where = 'WHERE 1=1';
    $params = [];
    
    if (!empty($search_term)) {
        $where .= ' AND (client_name LIKE ? OR invoice_number LIKE ? OR client_email LIKE ?)';
        $search_param = "%$search_term%";
        $params = array_merge($params, [$search_param, $search_param, $search_param]);
    }
    
    if (!empty($filters['status'])) {
        $where .= ' AND status = ?';
        $params[] = $filters['status'];
    }
    
    if (!empty($filters['date_from'])) {
        $where .= ' AND created_at >= ?';
        $params[] = $filters['date_from'];
    }
    
    if (!empty($filters['date_to'])) {
        $where .= ' AND created_at <= ?';
        $params[] = $filters['date_to'];
    }
    
    if (!empty($filters['amount_min'])) {
        $where .= ' AND total_amount >= ?';
        $params[] = $filters['amount_min'];
    }
    
    if (!empty($filters['amount_max'])) {
        $where .= ' AND total_amount <= ?';
        $params[] = $filters['amount_max'];
    }
    
    $stmt = $pdo->prepare("SELECT * FROM invoices $where ORDER BY created_at DESC");
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Admin authentication (simple implementation)
function checkAdminAuth() {
    session_start();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function adminLogin($password) {
    // Simple password check - in production, use proper authentication
    $admin_password = 'admin123'; // Change this in production
    
    if ($password === $admin_password) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function adminLogout() {
    session_start();
    unset($_SESSION['admin_logged_in']);
    session_destroy();
}

// Data cleanup functions
function cleanupOldDrafts($pdo, $days_old = 30) {
    $stmt = $pdo->prepare('DELETE FROM invoices WHERE status = "draft" AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)');
    return $stmt->execute([$days_old]);
}

function archivePaidInvoices($pdo, $days_old = 365) {
    // This would typically move to an archive table
    // For now, we'll just mark them as archived
    $stmt = $pdo->prepare('UPDATE invoices SET status = "archived" WHERE status = "paid" AND updated_at < DATE_SUB(NOW(), INTERVAL ? DAY)');
    return $stmt->execute([$days_old]);
}

// Backup functions
function createDatabaseBackup($pdo) {
    $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backup_path = '../backups/' . $backup_file;
    
    // Create backups directory if it doesn't exist
    if (!is_dir('../backups')) {
        mkdir('../backups', 0755, true);
    }
    
    // Simple backup - in production, use mysqldump
    $stmt = $pdo->query('SELECT * FROM invoices');
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $backup_content = "-- Invoice Generator Database Backup\n";
    $backup_content .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($invoices as $invoice) {
        $backup_content .= "INSERT INTO invoices VALUES (" . 
            implode(', ', array_map(function($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, $invoice)) . ");\n";
    }
    
    file_put_contents($backup_path, $backup_content);
    return $backup_file;
}

// Performance monitoring
function getPerformanceMetrics($pdo) {
    $metrics = [];
    
    // Query execution time
    $start_time = microtime(true);
    $pdo->query('SELECT COUNT(*) FROM invoices');
    $metrics['query_time'] = round((microtime(true) - $start_time) * 1000, 2);
    
    // Memory usage
    $metrics['memory_usage'] = round(memory_get_usage(true) / 1024 / 1024, 2);
    $metrics['memory_peak'] = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
    
    return $metrics;
}
?>
