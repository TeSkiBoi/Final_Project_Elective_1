<?php
/**
 * Financial Transaction Model
 * Handles financial transactions (income and expenses) for the barangay
 */

require_once __DIR__ . '/../Config/Database.php';

class Financial {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->connect();
    }

    /**
     * Get all financial transactions
     */
    public function getAll() {
        $query = "SELECT * FROM financial_transactions ORDER BY transaction_date DESC, transaction_id DESC";
        $result = $this->connection->query($query);
        
        $transactions = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $transactions[] = $row;
            }
        }
        return $transactions;
    }

    /**
     * Get transaction by ID
     */
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM financial_transactions WHERE transaction_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Create new transaction
     */
    public function create($data) {
        $stmt = $this->connection->prepare(
            "INSERT INTO financial_transactions 
            (transaction_date, transaction_type, category, amount, description, reference_number, payee_payer, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            "sssdssss",
            $data['transaction_date'],
            $data['transaction_type'],
            $data['category'],
            $data['amount'],
            $data['description'],
            $data['reference_number'],
            $data['payee_payer'],
            $data['payment_method']
        );
        
        return $stmt->execute();
    }

    /**
     * Update transaction
     */
    public function update($id, $data) {
        $stmt = $this->connection->prepare(
            "UPDATE financial_transactions 
            SET transaction_date = ?, transaction_type = ?, category = ?, amount = ?, 
                description = ?, reference_number = ?, payee_payer = ?, payment_method = ?
            WHERE transaction_id = ?"
        );
        
        $stmt->bind_param(
            "sssdssssi",
            $data['transaction_date'],
            $data['transaction_type'],
            $data['category'],
            $data['amount'],
            $data['description'],
            $data['reference_number'],
            $data['payee_payer'],
            $data['payment_method'],
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Delete transaction
     */
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM financial_transactions WHERE transaction_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get financial summary
     */
    public function getSummary() {
        $query = "SELECT * FROM current_balance";
        $result = $this->connection->query($query);
        return $result->fetch_assoc();
    }

    /**
     * Get transactions by type
     */
    public function getByType($type) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM financial_transactions WHERE transaction_type = ? ORDER BY transaction_date DESC"
        );
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        return $transactions;
    }

    /**
     * Get transactions by date range
     */
    public function getByDateRange($start_date, $end_date) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM financial_transactions 
            WHERE transaction_date BETWEEN ? AND ? 
            ORDER BY transaction_date DESC"
        );
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        return $transactions;
    }

    /**
     * Get monthly summary
     */
    public function getMonthlySummary($year, $month) {
        $stmt = $this->connection->prepare(
            "SELECT 
                transaction_type,
                category,
                SUM(amount) as total_amount,
                COUNT(*) as transaction_count
            FROM financial_transactions
            WHERE YEAR(transaction_date) = ? AND MONTH(transaction_date) = ?
            GROUP BY transaction_type, category
            ORDER BY transaction_type, total_amount DESC"
        );
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $summary = [];
        while ($row = $result->fetch_assoc()) {
            $summary[] = $row;
        }
        return $summary;
    }

    /**
     * Get income categories
     */
    public function getIncomeCategories() {
        $query = "SELECT DISTINCT category FROM financial_transactions WHERE transaction_type = 'Income' ORDER BY category";
        $result = $this->connection->query($query);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
        return $categories;
    }

    /**
     * Get expense categories
     */
    public function getExpenseCategories() {
        $query = "SELECT DISTINCT category FROM financial_transactions WHERE transaction_type = 'Expense' ORDER BY category";
        $result = $this->connection->query($query);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
        return $categories;
    }
}

