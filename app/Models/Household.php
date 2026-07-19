<?php
namespace App\Models;

class Household {
    private $db;
    private $table = 'households';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get all households with pagination
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
     * Create household with prepared statement
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['barangay_id'])) {
            throw new Exception('barangay_id is required');
        }

        try {
            $id = $this->db->executeInsert($this->table, [
                'barangay_id' => (int)$data['barangay_id'],
                'import_id' => isset($data['import_id']) ? (int)$data['import_id'] : null,
                'household_head' => $data['household_head'] ?? '',
                'address' => $data['address'] ?? '',
                'member_count' => (int)($data['member_count'] ?? 0),
                'socioeconomic_status' => $data['socioeconomic_status'] ?? 'Unknown'
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Create household error: ' . $e->getMessage());
        }
    }

    /**
     * Get socioeconomic data distribution
     */
    public function getSocioeconomicData() {
        try {
            $sql = "SELECT socioeconomic_status, COUNT(*) as count 
                    FROM {$this->table} 
                    GROUP BY socioeconomic_status";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get socioeconomic data error: ' . $e->getMessage());
        }
    }

    /**
     * Get households by barangay with type-safe parameter
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
     * Get total households count
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

    /**
     * Get households count by barangay
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

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
