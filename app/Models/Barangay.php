<?php
namespace App\Models;

class Barangay {
    private $db;
    private $table = 'barangays';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get all barangays
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all error: ' . $e->getMessage());
        }
    }

    /**
     * Get barangay by ID with prepared statement
     */
    public function getById($id) {
        $id = (int)$id;
        
        try {
            return $this->db->find($this->table, $id);
        } catch (Exception $e) {
            throw new Exception('Get by ID error: ' . $e->getMessage());
        }
    }

    /**
     * Create barangay with prepared statement
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['name'])) {
            throw new Exception('Barangay name is required');
        }

        try {
            return $this->db->executeInsert($this->table, [
                'name' => $data['name'],
                'population' => (int)($data['population'] ?? 0),
                'area' => (float)($data['area'] ?? 0),
                'chairman' => $data['chairman'] ?? '',
                'contact' => $data['contact'] ?? ''
            ]);
        } catch (Exception $e) {
            throw new Exception('Create barangay error: ' . $e->getMessage());
        }
    }

    /**
     * Update barangay with field validation
     */
    public function update($id, $data) {
        $id = (int)$id;
        
        if (empty($id)) {
            throw new Exception('Invalid barangay ID');
        }

        // Only allow specific fields
        $allowedFields = ['name', 'population', 'area', 'chairman', 'contact'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                if (in_array($field, ['population', 'area'])) {
                    $updateData[$field] = $field === 'population' ? (int)$data[$field] : (float)$data[$field];
                } else {
                    $updateData[$field] = $data[$field];
                }
            }
        }

        if (empty($updateData)) {
            throw new Exception('No valid fields to update');
        }

        try {
            return $this->db->executeUpdate($this->table, $updateData, $id);
        } catch (Exception $e) {
            throw new Exception('Update barangay error: ' . $e->getMessage());
        }
    }

    /**
     * Delete barangay
     */
    public function delete($id) {
        $id = (int)$id;
        
        if (empty($id)) {
            throw new Exception('Invalid barangay ID');
        }

        try {
            return $this->db->executeDelete($this->table, $id);
        } catch (Exception $e) {
            throw new Exception('Delete barangay error: ' . $e->getMessage());
        }
    }

    /**
     * Get barangay statistics with type-safe parameter
     */
    public function getStats($barangayId) {
        $barangayId = (int)$barangayId;
        
        try {
            $sql = "SELECT 
                        b.id, b.name, b.population,
                        (SELECT COUNT(*) FROM households WHERE barangay_id = b.id) as household_count,
                        (SELECT COUNT(*) FROM individuals WHERE barangay_id = b.id) as individual_count
                    FROM {$this->table} b 
                    WHERE b.id = {$barangayId}";
            
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get stats error: ' . $e->getMessage());
        }
    }

    /**
     * Get total barangay count
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
