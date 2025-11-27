<?php
/**
 * BarangayOfficial Model
 * Handles database operations for barangay officials organizational chart
 */

require_once __DIR__ . '/../Config/Database.php';

class BarangayOfficial {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    /**
     * Get all barangay officials ordered by display_order
     */
    public function getAll() {
        $query = "SELECT * FROM barangay_officials 
                  WHERE is_active = 'Yes' 
                  ORDER BY display_order ASC";
        $result = $this->connection->query($query);
        
        if (!$result) {
            return [];
        }
        
        $officials = [];
        while ($row = $result->fetch_assoc()) {
            $officials[] = $row;
        }
        return $officials;
    }

    /**
     * Get a single official by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM barangay_officials WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get official by position title
     */
    public function getByPosition($position_title) {
        $query = "SELECT * FROM barangay_officials WHERE position_title = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $position_title);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Update official information (name and/or image)
     */
    public function update($id, $data) {
        $query = "UPDATE barangay_officials 
                  SET full_name = ?, 
                      image_path = ?,
                      display_order = ?
                  WHERE id = ?";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param(
            "ssii",
            $data['full_name'],
            $data['image_path'],
            $data['display_order'],
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Update only the full name
     */
    public function updateName($id, $full_name) {
        $query = "UPDATE barangay_officials SET full_name = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $full_name, $id);
        return $stmt->execute();
    }

    /**
     * Update only the image path
     */
    public function updateImage($id, $image_path) {
        $query = "UPDATE barangay_officials SET image_path = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $image_path, $id);
        return $stmt->execute();
    }

    /**
     * Update display order (for drag & drop reordering)
     */
    public function updateOrder($id, $display_order) {
        $query = "UPDATE barangay_officials SET display_order = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii", $display_order, $id);
        return $stmt->execute();
    }

    /**
     * Create a new position (for future expansion)
     */
    public function create($data) {
        $query = "INSERT INTO barangay_officials 
                  (position_title, full_name, image_path, display_order, is_active) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param(
            "sssis",
            $data['position_title'],
            $data['full_name'],
            $data['image_path'],
            $data['display_order'],
            $data['is_active']
        );
        
        return $stmt->execute();
    }

    /**
     * Soft delete (set is_active to 'No')
     */
    public function delete($id) {
        $query = "UPDATE barangay_officials SET is_active = 'No' WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Hard delete (permanently remove)
     */
    public function permanentDelete($id) {
        $query = "DELETE FROM barangay_officials WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get officials grouped by hierarchy level
     */
    public function getByHierarchy() {
        $officials = $this->getAll();
        $hierarchy = [
            'chairman' => [],
            'executives' => [],
            'kagawads' => [],
            'youth_security' => []
        ];

        foreach ($officials as $official) {
            if (strpos($official['position_title'], 'Chairman') !== false && 
                strpos($official['position_title'], 'SK') === false) {
                $hierarchy['chairman'][] = $official;
            } elseif (strpos($official['position_title'], 'Secretary') !== false || 
                      strpos($official['position_title'], 'Treasurer') !== false) {
                $hierarchy['executives'][] = $official;
            } elseif (strpos($official['position_title'], 'Kagawad') !== false) {
                $hierarchy['kagawads'][] = $official;
            } else {
                $hierarchy['youth_security'][] = $official;
            }
        }

        return $hierarchy;
    }

    /**
     * Count total officials
     */
    public function countOfficials() {
        $query = "SELECT COUNT(*) as total FROM barangay_officials WHERE is_active = 'Yes'";
        $result = $this->connection->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    /**
     * Check if position already exists
     */
    public function positionExists($position_title, $excludeId = null) {
        if ($excludeId) {
            $query = "SELECT COUNT(*) as count FROM barangay_officials 
                      WHERE position_title = ? AND id != ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("si", $position_title, $excludeId);
        } else {
            $query = "SELECT COUNT(*) as count FROM barangay_officials 
                      WHERE position_title = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $position_title);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}