-- ============================================================================
-- FINANCIAL MANAGEMENT SYSTEM SETUP
-- ============================================================================
-- Description: Creates tables for barangay financial management system
-- Date: 2024
-- ============================================================================

-- Drop tables if they exist
DROP TABLE IF EXISTS financial_reports;
DROP TABLE IF EXISTS financial_transactions;

-- Create financial_transactions table
CREATE TABLE financial_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_date DATE NOT NULL,
    transaction_type ENUM('Income', 'Expense') NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    description TEXT,
    reference_number VARCHAR(50),
    payee_payer VARCHAR(255),
    payment_method ENUM('Cash', 'Check', 'Bank Transfer', 'Online Payment', 'Other') DEFAULT 'Cash',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_transaction_date (transaction_date),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create financial_reports table
CREATE TABLE financial_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_name VARCHAR(255) NOT NULL,
    report_type ENUM('Monthly', 'Quarterly', 'Annual', 'Custom') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_income DECIMAL(12, 2) DEFAULT 0.00,
    total_expense DECIMAL(12, 2) DEFAULT 0.00,
    net_balance DECIMAL(12, 2) DEFAULT 0.00,
    generated_by INT,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_report_type (report_type),
    INDEX idx_date_range (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample income categories
INSERT INTO financial_transactions (transaction_date, transaction_type, category, amount, description, payee_payer, payment_method) VALUES
('2024-01-15', 'Income', 'Barangay Allocation', 50000.00, 'Monthly allocation from city government', 'City Government', 'Bank Transfer'),
('2024-01-20', 'Income', 'Community Tax', 15000.00, 'Community tax collection', 'Residents', 'Cash'),
('2024-01-25', 'Income', 'Permit Fees', 8000.00, 'Business permit fees', 'Business Owners', 'Cash'),
('2024-02-01', 'Expense', 'Infrastructure', 25000.00, 'Road repair and maintenance', 'ABC Construction', 'Check'),
('2024-02-05', 'Expense', 'Office Supplies', 3500.00, 'Paper, pens, and office materials', 'Office Supply Store', 'Cash'),
('2024-02-10', 'Expense', 'Utilities', 4200.00, 'Electricity and water bills', 'Utility Company', 'Bank Transfer');

-- Create view for financial summary
CREATE OR REPLACE VIEW financial_summary AS
SELECT 
    YEAR(transaction_date) AS year,
    MONTH(transaction_date) AS month,
    transaction_type,
    category,
    SUM(amount) AS total_amount,
    COUNT(*) AS transaction_count
FROM financial_transactions
GROUP BY YEAR(transaction_date), MONTH(transaction_date), transaction_type, category
ORDER BY year DESC, month DESC;

-- Create view for current balance
CREATE OR REPLACE VIEW current_balance AS
SELECT 
    (SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE transaction_type = 'Income') AS total_income,
    (SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE transaction_type = 'Expense') AS total_expense,
    (SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE transaction_type = 'Income') - 
    (SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE transaction_type = 'Expense') AS net_balance;

-- ============================================================================
-- VERIFICATION
-- ============================================================================
SELECT * FROM financial_transactions ORDER BY transaction_date DESC LIMIT 10;
SELECT * FROM current_balance;
SELECT * FROM financial_summary LIMIT 10;

-- ============================================================================
-- NOTES
-- ============================================================================
-- 1. financial_transactions: Stores all income and expense transactions
-- 2. financial_reports: Stores generated financial reports
-- 3. financial_summary: View for summarized financial data
-- 4. current_balance: View for current financial balance
-- ============================================================================
