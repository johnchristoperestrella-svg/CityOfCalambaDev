<?php
namespace App\Models;

class DataImport {
    private $db;
    private $table = 'data_imports';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Create a new data import record with prepared statement
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['file_name'])) {
            throw new Exception('Missing required fields');
        }

        try {
            $id = $this->db->executeInsert($this->table, [
                'user_id' => (int)$data['user_id'],
                'file_name' => $data['file_name'],
                'file_path' => $data['file_path'],
                'barangay_id' => (int)($data['barangay_id'] ?? 0),
                'total_records' => (int)($data['total_records'] ?? 0),
                'processed_records' => (int)($data['processed_records'] ?? 0),
                'status' => 'pending',
                'import_date' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Create import error: ' . $e->getMessage());
        }
    }

    /**
     * Get import by ID with prepared statement
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
     * Get imports by user with type-safe parameter binding
     */
    public function getByUser($userId) {
        $userId = (int)$userId;
        
        try {
            $sql = "SELECT di.*, b.name as barangay_name, u.email as uploader_email 
                    FROM {$this->table} di
                    LEFT JOIN barangays b ON di.barangay_id = b.id
                    LEFT JOIN users u ON di.user_id = u.id
                    WHERE di.user_id = ?
                    ORDER BY di.import_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get by user error: ' . $e->getMessage());
        }
    }

    /**
     * Get all imports with pagination
     */
    public function getAllImports($page = 1, $limit = 50) {
        $limit = (int)$limit;
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT di.*, b.name as barangay_name, u.email as uploader_email 
                    FROM {$this->table} di
                    LEFT JOIN barangays b ON di.barangay_id = b.id
                    LEFT JOIN users u ON di.user_id = u.id
                    ORDER BY di.import_date DESC 
                    LIMIT {$limit} OFFSET {$offset}";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all imports error: ' . $e->getMessage());
        }
    }

    /**
     * Get total import count
     */
    public function getImportCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get count error: ' . $e->getMessage());
        }
    }

    /**
     * Update import status with validation
     */
    public function updateStatus($id, $status) {
        $id = (int)$id;
        
        // Validate status
        $validStatuses = ['pending', 'processing', 'completed', 'failed'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid status');
        }

        try {
            return $this->db->executeUpdate($this->table, ['status' => $status], $id);
        } catch (Exception $e) {
            throw new Exception('Update status error: ' . $e->getMessage());
        }
    }

    /**
     * Update processed records count
     */
    public function updateProcessedRecords($id, $processedRecords) {
        $id = (int)$id;
        $processedRecords = (int)$processedRecords;
        
        try {
            return $this->db->executeUpdate(
                $this->table, 
                ['processed_records' => $processedRecords], 
                $id
            );
        } catch (Exception $e) {
            throw new Exception('Update processed records error: ' . $e->getMessage());
        }
    }

    /**
     * Update both status and processed records atomically
     */
    public function updateStatusAndRecords($id, $status, $processedRecords) {
        $id = (int)$id;
        $processedRecords = (int)$processedRecords;
        
        // Validate status
        $validStatuses = ['pending', 'processing', 'completed', 'failed'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid status');
        }

        try {
            return $this->db->executeUpdate(
                $this->table,
                ['status' => $status, 'processed_records' => $processedRecords],
                $id
            );
        } catch (Exception $e) {
            throw new Exception('Update status and records error: ' . $e->getMessage());
        }
    }

    /**
     * Delete import record
     */
    public function delete($id) {
        $id = (int)$id;
        
        try {
            return $this->db->executeDelete($this->table, $id);
        } catch (Exception $e) {
            throw new Exception('Delete error: ' . $e->getMessage());
        }
    }

    /**
     * Get import statistics
     */
    public function getStats() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_imports,
                        SUM(total_records) as total_records_uploaded,
                        SUM(processed_records) as total_records_processed,
                        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_imports
                    FROM {$this->table}";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get stats error: ' . $e->getMessage());
        }
    }
}
