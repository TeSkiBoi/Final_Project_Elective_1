<?php
/**
 * Department Model
 * Handles database operations for departments
 */

require_once __DIR__ . '/../Config/Database.php';

class Dashboard {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }
    /**
     * ========================================
     * BARANGAY MANAGEMENT SYSTEM - DASHBOARD METRICS
     * ========================================
     */

    /**
     * COUNT TOTAL RESIDENTS
     */
    public function getCountResidents(){
        $query = "SELECT COUNT(*) as resident_count FROM residents";
        $result = $this->connection->query($query);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_assoc();
        return $row['resident_count'];
    }

    /**
     * COUNT TOTAL HOUSEHOLDS
     */
    public function getCountHouseholds(){
        $query = "SELECT COUNT(*) as household_count FROM households";
        $result = $this->connection->query($query);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_assoc();
        return $row['household_count'];
    }

    /**
     * COUNT ACTIVE BLOTTER CASES (Pending + Under Investigation)
     */
    public function getActiveBlotterCases(){
        $query = "SELECT COUNT(*) as active_cases FROM blotter_incidents 
                  WHERE incident_status IN ('Pending', 'Under Investigation', 'For Mediation')";
        $result = $this->connection->query($query);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_assoc();
        return $row['active_cases'];
    }

    /**
     * GET BUDGET UTILIZATION PERCENTAGE (Current Month)
     */
    public function getBudgetUtilization(){
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END), 0) as total_income,
                    COALESCE(SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END), 0) as total_expense
                  FROM financial_transactions 
                  WHERE MONTH(transaction_date) = MONTH(CURDATE()) 
                  AND YEAR(transaction_date) = YEAR(CURDATE())";
        $result = $this->connection->query($query);
        if (!$result) {
            return ['percentage' => 0, 'income' => 0, 'expense' => 0];
        }
        $row = $result->fetch_assoc();
        $income = floatval($row['total_income']);
        $expense = floatval($row['total_expense']);
        $percentage = $income > 0 ? round(($expense / $income) * 100, 1) : 0;
        return [
            'percentage' => $percentage,
            'income' => $income,
            'expense' => $expense
        ];
    }

    /**
     * GET POPULATION BY AGE GROUP (Children, Adults, Seniors)
     */
    public function getPopulationByAgeGroup(){
        $query = "SELECT 
                    SUM(CASE WHEN age < 18 THEN 1 ELSE 0 END) as children,
                    SUM(CASE WHEN age BETWEEN 18 AND 59 THEN 1 ELSE 0 END) as adults,
                    SUM(CASE WHEN age >= 60 THEN 1 ELSE 0 END) as seniors
                  FROM residents";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $row = $result->fetch_assoc();
        return [
            ['age_group' => 'Children (0-17)', 'count' => intval($row['children'])],
            ['age_group' => 'Adults (18-59)', 'count' => intval($row['adults'])],
            ['age_group' => 'Seniors (60+)', 'count' => intval($row['seniors'])]
        ];
    }

    /**
     * GET GENDER DISTRIBUTION
     * Note: Gender field removed from new schema, returning household-based stats instead
     */
    public function getGenderDistribution(){
        // Since gender field no longer exists in new resident schema,
        // we'll show household distribution as alternative metric
        $query = "SELECT 
                    'With Contact' as gender,
                    COUNT(*) as count 
                  FROM residents 
                  WHERE contact_no IS NOT NULL AND contact_no != ''
                  UNION ALL
                  SELECT 
                    'Without Contact' as gender,
                    COUNT(*) as count 
                  FROM residents 
                  WHERE contact_no IS NULL OR contact_no = ''";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $gender_dist = [];
        while ($row = $result->fetch_assoc()) {
            $gender_dist[] = $row;
        }
        return $gender_dist;
    }

    /**
     * GET INCIDENT TRENDS (Monthly - Current Year)
     */
    public function getIncidentTrends(){
        $query = "SELECT 
                    MONTH(incident_date) as month,
                    DATE_FORMAT(incident_date, '%b') as month_name,
                    COUNT(*) as incident_count
                  FROM blotter_incidents
                  WHERE YEAR(incident_date) = YEAR(CURDATE())
                  GROUP BY MONTH(incident_date), DATE_FORMAT(incident_date, '%b')
                  ORDER BY MONTH(incident_date)";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $incident_trends = [];
        while ($row = $result->fetch_assoc()) {
            $incident_trends[] = $row;
        }
        return $incident_trends;
    }

    /**
     * GET BUDGET VS EXPENSES BY CATEGORY (Current Year)
     */
    public function getBudgetVsExpensesByCategory(){
        $query = "SELECT 
                    category,
                    SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END) as budget,
                    SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END) as expense
                  FROM financial_transactions
                  WHERE YEAR(transaction_date) = YEAR(CURDATE())
                  GROUP BY category
                  ORDER BY budget DESC
                  LIMIT 8";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $budget_expense = [];
        while ($row = $result->fetch_assoc()) {
            $budget_expense[] = $row;
        }
        return $budget_expense;
    }

    /**
     * GET PROJECT STATUS DISTRIBUTION
     */
    public function getProjectStatus(){
        $query = "SELECT 
                    project_status as status,
                    COUNT(*) as count
                  FROM barangay_projects
                  GROUP BY project_status
                  ORDER BY FIELD(project_status, 'Completed', 'Ongoing', 'Planned')";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $project_status = [];
        while ($row = $result->fetch_assoc()) {
            $project_status[] = $row;
        }
        return $project_status;
    }

    /**
     * GET RECENT BLOTTER CASES (Last 5)
     */
    public function getRecentBlotterCases(){
        $query = "SELECT 
                    case_number,
                    incident_type,
                    incident_date,
                    complainant_name,
                    incident_status,
                    priority_level
                  FROM blotter_incidents
                  ORDER BY created_at DESC
                  LIMIT 5";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $recent_cases = [];
        while ($row = $result->fetch_assoc()) {
            $recent_cases[] = $row;
        }
        return $recent_cases;
    }

    /**
     * GET INCIDENT STATUS DISTRIBUTION
     */
    public function getIncidentStatusDistribution(){
        $query = "SELECT 
                    incident_status as status,
                    COUNT(*) as count
                  FROM blotter_incidents
                  GROUP BY incident_status
                  ORDER BY count DESC";
        $result = $this->connection->query($query);
        if (!$result) {
            return [];
        }
        $status_dist = [];
        while ($row = $result->fetch_assoc()) {
            $status_dist[] = $row;
        }
        return $status_dist;
    }

    /**
     * COUNT TOTAL SYSTEM USERS
     */
    public function getCountUser(){
        $query = "SELECT COUNT(*) as user_count FROM users";
        $result = $this->connection->query($query);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_assoc();
        return $row['user_count'];
    }


}
?>
