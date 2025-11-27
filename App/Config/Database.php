<?php
/**
 * Database Configuration Class
 * Handles database connection using MySQLi
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'student_information_system';
    private $username = 'root';
    private $password = '';
    private $connection;

    /**
     * Connect to Database
     */
    public function connect() {
        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->db_name
        );

        // Check connection
        if ($this->connection->connect_error) {
            // Return JSON error instead of HTML
            if (headers_sent()) {
                throw new Exception('Database connection failed: ' . $this->connection->connect_error);
            }
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database connection failed']);
            exit;
        }

        return $this->connection;
    }

    /**
     * Get Connection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Close Connection
     */
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}