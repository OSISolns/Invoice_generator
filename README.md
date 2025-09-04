# Invoice Generator

A modern, full-featured invoice management system built with PHP and MySQL. Create, manage, and track invoices with a beautiful, responsive web interface.

## Features

### Main Application
- ✅ **Complete CRUD Operations**: Create, read, update, and delete invoices
- ✅ **Modern UI**: Bootstrap 5 with responsive design and Font Awesome icons
- ✅ **Invoice Management**: Track invoice status (Draft, Sent, Paid, Overdue)
- ✅ **Tax Calculations**: Automatic tax calculation and total amount computation
- ✅ **Client Management**: Store client information including name, email, and address
- ✅ **Status Filtering**: Filter invoices by status
- ✅ **Real-time Totals**: Dashboard showing invoice statistics and totals
- ✅ **Input Validation**: Server-side validation with user-friendly error messages
- ✅ **Security**: SQL injection protection with prepared statements
- ✅ **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices

### Admin Dashboard
- ✅ **Comprehensive Analytics**: Revenue trends, status distribution, and performance metrics
- ✅ **Advanced Search & Filtering**: Search invoices by multiple criteria
- ✅ **Bulk Operations**: Update status or delete multiple invoices at once
- ✅ **Data Export**: Export invoices to CSV format
- ✅ **System Monitoring**: Database statistics, performance metrics, and system info
- ✅ **Data Management**: Cleanup old drafts, create backups, and archive data
- ✅ **Interactive Charts**: Visual analytics with Chart.js integration
- ✅ **Admin Authentication**: Secure login system for administrative access

## Screenshots

The application features:
- Clean, professional interface with gradient cards
- Comprehensive invoice form with all necessary fields
- Status-based filtering and color-coded badges
- Modal dialogs for status updates and confirmations
- Real-time tax calculations
- Responsive table layout for invoice listing

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PDO MySQL extension

## Installation

### 1. Clone or Download
```bash
git clone <repository-url>
# or download and extract the ZIP file
```

### 2. Database Setup
1. Create a MySQL database named `invoice_generator`
2. Import the database schema:
```bash
mysql -u root -p invoice_generator < database/schema.sql
```

### 3. Configuration
Update the database credentials in `src/config.php`:
```php
$host = 'localhost';
$dbname = 'invoice_generator';
$username = 'your_username';
$password = 'your_password';
```

### 4. Web Server Setup
- Place the project files in your web server's document root
- Ensure the web server has read/write permissions
- Configure your web server to serve PHP files

### 5. Access the Application
Open your web browser and navigate to:
```
http://localhost/Invoice_generator/src/
```

### 6. Admin Dashboard Access
Access the admin dashboard at:
```
http://localhost/Invoice_generator/src/admin/
```
**Default admin password**: `admin123` (change this in production!)

## Usage

### Creating an Invoice
1. Click "New Invoice" button
2. Fill in the required fields:
   - Client Name (required)
   - Amount (required)
   - Issue Date (required)
   - Due Date (required)
3. Optionally add:
   - Client email and address
   - Tax rate (percentage)
   - Description
   - Status
4. Click "Create Invoice"

### Managing Invoices
- **Edit**: Click the edit icon to modify an invoice
- **Update Status**: Use the sync icon to change invoice status
- **Delete**: Click the trash icon to remove an invoice
- **Filter**: Use the status buttons to filter invoices

### Invoice Statuses
- **Draft**: Invoice is being prepared
- **Sent**: Invoice has been sent to client
- **Paid**: Payment has been received
- **Overdue**: Payment is past due date

## File Structure

```
Invoice_generator/
├── database/
│   └── schema.sql          # Database schema and sample data
├── src/
│   ├── config.php          # Database configuration
│   ├── functions.php       # Business logic and utility functions
│   ├── index.php          # Main application entry point
│   └── templates/
│       └── main.php       # Main HTML template
└── README.md              # This file
```

## Database Schema

The application uses a single `invoices` table with the following structure:

```sql
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_email VARCHAR(255),
    client_address TEXT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    tax_rate DECIMAL(5, 2) DEFAULT 0.00,
    tax_amount DECIMAL(10, 2) DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    due_date DATE NOT NULL,
    issue_date DATE NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue') DEFAULT 'draft',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Security Features

- **SQL Injection Protection**: All database queries use prepared statements
- **Input Sanitization**: User input is sanitized using `htmlspecialchars()`
- **Input Validation**: Server-side validation for all form fields
- **XSS Prevention**: Output is properly escaped

## Customization

### Styling
The application uses Bootstrap 5 with custom CSS. You can modify the styles in the `<style>` section of `main.php`.

### Tax Rates
Default tax rate is 0%. You can set different default tax rates by modifying the form defaults.

### Invoice Numbering
Invoice numbers are auto-generated in the format `INV-001`, `INV-002`, etc. You can modify the `generateInvoiceNumber()` function in `functions.php` to change the format.

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Errors**
   - Ensure web server has read/write permissions
   - Check file ownership

3. **PHP Errors**
   - Enable error reporting in PHP
   - Check PHP version compatibility
   - Ensure PDO MySQL extension is installed

### Error Logs
Check your web server error logs for detailed error information.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support, please open an issue in the repository or contact the development team.

## Changelog

### Version 1.0.0
- Initial release
- Complete CRUD functionality
- Modern responsive UI
- Tax calculations
- Status management
- Input validation and security features
