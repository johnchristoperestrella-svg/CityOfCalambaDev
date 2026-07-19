<?php
namespace App\Models;

class Individual {
    private $db;
    private $table = 'individuals';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get all individuals with optional barangay filter and pagination
     */
    public function getAll($barangayId = null, $page = 1, $limit = 50) {
        $offset = ((int)$page - 1) * $limit;
        
        try {
            if ($barangayId) {
                $barangayId = (int)$barangayId;
                $sql = "SELECT * FROM {$this->table} 
                        WHERE barangay_id = {$barangayId}
                        ORDER BY created_at DESC 
                        LIMIT {$limit} OFFSET {$offset}";
            } else {
                $sql = "SELECT * FROM {$this->table} 
                        ORDER BY created_at DESC 
                        LIMIT {$limit} OFFSET {$offset}";
            }
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all error: ' . $e->getMessage());
        }
    }

    /**
     * Create individual with prepared statement
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['barangay_id']) || empty($data['first_name'])) {
            throw new Exception('Missing required fields');
        }

        // Validate gender
        $validGenders = ['Male', 'Female', 'Other'];
        if (!in_array($data['gender'], $validGenders)) {
            throw new Exception('Invalid gender value');
        }

        try {
            $id = $this->db->executeInsert($this->table, [
                'barangay_id' => (int)$data['barangay_id'],
                'import_id' => isset($data['import_id']) ? (int)$data['import_id'] : null,
                'household_id' => (int)($data['household_id'] ?? 0),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? '',
                'age' => (int)($data['age'] ?? 0),
                'gender' => $data['gender'],
                'health_status' => $data['health_status'] ?? 'Unknown',
                'education_level' => $data['education_level'] ?? 'Unknown'
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Create individual error: ' . $e->getMessage());
        }
    }

    /**
     * Get individuals by barangay with type-safe parameter
     */
    public function getByBarangay($barangayId, $page = 1, $limit = 50) {
        $barangayId = (int)$barangayId;
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE barangay_id = {$barangayId}
                    ORDER BY created_at DESC
                    LIMIT {$limit} OFFSET {$offset}";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get by barangay error: ' . $e->getMessage());
        }
    }

    /**
     * Get count by barangay
     */
    public function getCountByBarangay($barangayId) {
        $barangayId = (int)$barangayId;
        
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE barangay_id = {$barangayId}";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get count error: ' . $e->getMessage());
        }
    }

    /**
     * Get total count of all individuals
     */
    public function getTotalCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get total count error: ' . $e->getMessage());
        }
    }
}
