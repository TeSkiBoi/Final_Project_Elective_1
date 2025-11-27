# Financial Management System - Setup Guide

## Overview
The Financial Management module provides comprehensive tools to track and manage barangay financial activities including income tracking, expense management, and financial reporting.

## Setup Instructions

### Step 1: Run SQL Migration

Execute the SQL setup file to create necessary database tables and views:

```sql
SOURCE c:/xampp/htdocs/FINAL_PROJECT_ELECTIVE1/database/migrations/financial_management_setup.sql;
```

Or manually run the SQL file in phpMyAdmin:
1. Open phpMyAdmin
2. Select your database
3. Go to "Import" tab
4. Choose file: `database/migrations/financial_management_setup.sql`
5. Click "Go"

### Step 2: Verify Installation

Check that the following tables and views were created:
- `financial_transactions` - Main transactions table
- `financial_reports` - Reports storage table
- `financial_summary` - Summary view
- `current_balance` - Balance calculation view

Run this query to verify:
```sql
SHOW TABLES LIKE 'financial%';
SELECT * FROM current_balance;
```

### Step 3: Access the Module

Navigate to: `http://localhost/FINAL_PROJECT_ELECTIVE1/App/View/financial.php`

Or click "Financial Management" in the sidebar under Features section.

## Features

### 1. **Transaction Management**
- Add new income and expense transactions
- Edit existing transactions
- Delete transactions with confirmation
- Track transaction details including:
  - Date, Type (Income/Expense), Category
  - Amount, Payee/Payer, Payment Method
  - Reference Number, Description

### 2. **Financial Dashboard**
- Real-time financial summary cards:
  - **Total Income** - All barangay income sources
  - **Total Expenses** - All expenditures
  - **Net Balance** - Current financial status
- Comprehensive transaction table with sorting and filtering

### 3. **Transaction Categories**

**Income Categories:**
- Barangay Allocation
- Community Tax
- Permit Fees
- Donations
- Other Income Sources

**Expense Categories:**
- Infrastructure
- Office Supplies
- Utilities
- Salaries
- Maintenance
- Other Expenses

### 4. **Payment Methods**
- Cash
- Check
- Bank Transfer
- Online Payment
- Other

## File Structure

```
FINAL_PROJECT_ELECTIVE1/
├── App/
│   ├── Model/
│   │   └── Financial.php              # Financial data model
│   ├── Controller/
│   │   └── FinancialController.php    # API endpoints
│   └── View/
│       └── financial.php              # Main interface
└── database/
    └── migrations/
        └── financial_management_setup.sql  # Database setup
```

## Database Schema

### financial_transactions Table
```sql
- transaction_id (INT, AUTO_INCREMENT, PRIMARY KEY)
- transaction_date (DATE, NOT NULL)
- transaction_type (ENUM: 'Income', 'Expense', NOT NULL)
- category (VARCHAR(100), NOT NULL)
- amount (DECIMAL(12,2), NOT NULL)
- description (TEXT)
- reference_number (VARCHAR(50))
- payee_payer (VARCHAR(255))
- payment_method (ENUM: 'Cash', 'Check', 'Bank Transfer', 'Online Payment', 'Other')
- created_by (INT)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### current_balance View
```sql
- total_income (DECIMAL)
- total_expense (DECIMAL)
- net_balance (DECIMAL)
```

## API Endpoints

### FinancialController.php

**Base URL:** `../../App/Controller/FinancialController.php`

**Actions:**
- `?action=create` - Create new transaction (POST)
- `?action=update` - Update transaction (POST)
- `?action=delete` - Delete transaction (POST)
- `?action=getById&id={id}` - Get transaction by ID (GET)
- `?action=getSummary` - Get financial summary (GET)
- `?action=getByType&type={Income|Expense}` - Get by type (GET)

## Usage Examples

### Adding Income
1. Click "Add Transaction" button
2. Select "Income" as transaction type
3. Enter category (e.g., "Barangay Allocation")
4. Enter amount (e.g., 50000.00)
5. Fill in payee/payer and other details
6. Click "Create Transaction"

### Adding Expense
1. Click "Add Transaction" button
2. Select "Expense" as transaction type
3. Enter category (e.g., "Infrastructure")
4. Enter amount (e.g., 25000.00)
5. Fill in vendor/supplier details
6. Click "Create Transaction"

### Editing Transaction
1. Click edit icon (pencil) on any transaction row
2. Update desired fields
3. Click "Update Transaction"

### Deleting Transaction
1. Click delete icon (trash) on any transaction row
2. Review transaction details in confirmation modal
3. Click "Delete Transaction" to confirm

## Security Features

- **Authentication Protection:** Requires user login
- **RBAC Protection:** Admin and Staff roles only
- **Input Validation:** All fields validated on server-side
- **SQL Injection Prevention:** Prepared statements used
- **XSS Protection:** All output properly escaped

## Sample Data

The setup includes sample transactions:
- Income: Barangay Allocation (₱50,000)
- Income: Community Tax (₱15,000)
- Income: Permit Fees (₱8,000)
- Expense: Infrastructure (₱25,000)
- Expense: Office Supplies (₱3,500)
- Expense: Utilities (₱4,200)

## Troubleshooting

### Issue: "Table doesn't exist"
**Solution:** Run the SQL migration file again

### Issue: "Permission denied"
**Solution:** Ensure user has Admin or Staff role

### Issue: "Cannot connect to database"
**Solution:** Check Database.php configuration

### Issue: Summary cards show ₱0.00
**Solution:** Verify transactions exist in database

## Future Enhancements

Possible additions:
- Monthly/Quarterly/Annual reports generation
- Budget planning and tracking
- Category-based spending analysis
- Export to PDF/Excel
- Financial charts and graphs
- Budget vs. actual comparison
- Automated report scheduling

## Support

For issues or questions:
1. Check error logs in browser console
2. Verify database connection
3. Ensure all files are properly uploaded
4. Check user permissions (Admin/Staff only)

## Completion Status

✅ Database tables created
✅ Model layer implemented
✅ Controller API endpoints created
✅ View interface completed
✅ CRUD operations functional
✅ Financial summary dashboard
✅ Transaction management
✅ Form validation
✅ Modal dialogs
✅ SweetAlert notifications
✅ Responsive design
✅ Security implemented

---

**Module Status:** FULLY FUNCTIONAL ✅

The Financial Management system is ready for production use!
