<?php
namespace App\Models;

class User {
    private $db;
    private $table = 'users';
    // Whitelist of allowed fields for update operations
    private $allowedUpdateFields = ['email', 'name', 'status', 'profile_photo'];

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Create a new user with prepared statement
     */
    public function create($data) {
        // Validate required fields
        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            throw new \Exception('Missing required fields: email, password, name');
        }

        try {
            $id = $this->db->executeInsert($this->table, [
                'email' => $data['email'],
                'password' => $data['password'],
                'name' => $data['name'],
                'role' => $data['role'] ?? 'Data Encoder',
                'status' => 'active'
            ]);
            return $id;
        } catch (\Exception $e) {
            throw new \Exception('Create user error: ' . $e->getMessage());
        }
    }

    /**
     * Find user by email with prepared statement
     */
    public function findByEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (\Exception $e) {
            throw new \Exception('Find by email error: ' . $e->getMessage());
        }
    }

    /**
     * Find user by ID with prepared statement
     */
    public function findById($id) {
        // Type casting for safety
        $id = (int)$id;
        
        try {
            return $this->db->find($this->table, $id);
        } catch (\Exception $e) {
            throw new \Exception('Find by ID error: ' . $e->getMessage());
        }
    }

    /**
     * Get all users (non-admin) with pagination
     */
    public function getAll($filter = null, $page = 1, $limit = 50) {
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT id, email, name, role, status, created_at FROM {$this->table} 
                    WHERE role != 'City Administrator' 
                    ORDER BY created_at DESC 
                    LIMIT {$limit} OFFSET {$offset}";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception('Get all error: ' . $e->getMessage());
        }
    }

    /**
     * Get total user count
     */
    public function getCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE role != 'City Administrator'";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (\Exception $e) {
            throw new \Exception('Get count error: ' . $e->getMessage());
        }
    }

    /**
     * Update user with whitelist validation
     * Only allows updating specific fields to prevent privilege escalation
     */
    public function update($id, $data) {
        $id = (int)$id;
        
        if (empty($id)) {
            throw new \Exception('Invalid user ID');
        }

        // Filter to only allowed fields (prevent field injection)
        $allowedData = [];
        foreach ($this->allowedUpdateFields as $field) {
            if (array_key_exists($field, $data)) {
                $allowedData[$field] = $data[$field];
            }
        }

        if (empty($allowedData)) {
            throw new \Exception('No valid fields to update');
        }

        try {
            return $this->db->executeUpdate($this->table, $allowedData, $id);
        } catch (\Exception $e) {
            throw new \Exception('Update error: ' . $e->getMessage());
        }
    }

    /**
     * Delete user with prepared statement
     */
    public function delete($id) {
        $id = (int)$id;
        
        if (empty($id)) {
            throw new \Exception('Invalid user ID');
        }

        try {
            return $this->db->executeDelete($this->table, $id);
        } catch (\Exception $e) {
            throw new \Exception('Delete error: ' . $e->getMessage());
        }
    }

    /**
     * Update user role (admin only)
     */
    public function updateRole($id, $role) {
        $id = (int)$id;
        
        if (empty($id)) {
            throw new \Exception('Invalid user ID');
        }

        // Validate role
        $validRoles = ['Data Encoder', 'Barangay Coordinator', 'City Administrator', 'Data Validator'];
        if (!in_array($role, $validRoles)) {
            throw new \Exception('Invalid role');
        }

        try {
            return $this->db->executeUpdate($this->table, ['role' => $role], $id);
        } catch (\Exception $e) {
            throw new \Exception('Update role error: ' . $e->getMessage());
        }
    }

    /**
     * Get total user count (non-admin users)
     */
    public function getTotalCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE role != 'City Administrator'";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (\Exception $e) {
            throw new \Exception('Get total count error: ' . $e->getMessage());
        }
    }
}
