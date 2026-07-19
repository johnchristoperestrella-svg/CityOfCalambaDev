<?php
namespace App\Models;

class AuditLog {
    private $db;
    private $table = 'audit_logs';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Log an action with prepared statement
     */
    public function log($action, $details, $userId) {
        try {
            // Get safe IP address
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            
            return $this->db->executeInsert($this->table, [
                'user_id' => $userId ? (int)$userId : null,
                'action' => $action,
                'details' => $details,
                'ip_address' => $ipAddress,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            throw new Exception('Audit log error: ' . $e->getMessage());
        }
    }

    /**
     * Get all audit logs with pagination
     */
    public function getAll($limit = 50, $page = 1) {
        $limit = (int)$limit;
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT al.*, u.email, u.name FROM {$this->table} al 
                    LEFT JOIN users u ON al.user_id = u.id 
                    ORDER BY al.timestamp DESC 
                    LIMIT {$limit} OFFSET {$offset}";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all error: ' . $e->getMessage());
        }
    }

    /**
     * Get audit logs by user with type-safe parameter
     */
    public function getByUser($userId, $limit = 50, $page = 1) {
        $userId = (int)$userId;
        $limit = (int)$limit;
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = {$userId}
                    ORDER BY timestamp DESC 
                    LIMIT {$limit} OFFSET {$offset}";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get by user error: ' . $e->getMessage());
        }
    }

    /**
     * Get total audit logs count
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
