<?php
namespace App\Models;

class Document {
    private $db;
    private $table = 'documents';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get all documents with optional category filter
     */
    public function getAll($category = null, $page = 1, $limit = 50) {
        $offset = ((int)$page - 1) * $limit;
        
        try {
            $sql = "SELECT * FROM {$this->table}";
            if ($category) {
                // Validate category (prepared statement would be better here)
                $category = preg_replace('/[^a-zA-Z0-9_-]/', '', $category);
                $sql .= " WHERE category = '{$this->db->escape($category)}'";
            }
            $sql .= " ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";
            
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all error: ' . $e->getMessage());
        }
    }

    /**
     * Get document categories
     */
    public function getByCategory() {
        try {
            $sql = "SELECT category, COUNT(*) as count FROM {$this->table} GROUP BY category";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get by category error: ' . $e->getMessage());
        }
    }

    /**
     * Create document with prepared statement
     */
    public function create($data) {
        try {
            $uploadedBy = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            
            return $this->db->executeInsert($this->table, [
                'title' => $data['title'],
                'category' => $data['category'],
                'file_path' => $data['file_path'],
                'file_type' => $data['file_type'],
                'uploaded_by' => $uploadedBy,
                'views' => 0
            ]);
        } catch (Exception $e) {
            throw new Exception('Create document error: ' . $e->getMessage());
        }
    }

    /**
     * Get total document count directly from database
     */
    public function getTotalCount($category = null) {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
            if ($category) {
                $category = preg_replace('/[^a-zA-Z0-9_-]/', '', $category);
                $sql .= " WHERE category = '{$this->db->escape($category)}'";
            }
            
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get total count error: ' . $e->getMessage());
        }
    }

    /**
     * Get total number of categories
     */
    public function getTotalCategories() {
        try {
            $sql = "SELECT COUNT(DISTINCT category) as cnt FROM {$this->table}";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get total categories error: ' . $e->getMessage());
        }
    }

    /**
     * Increment view count with type-safe parameter
     */
    public function incrementViews($id) {
        $id = (int)$id;
        
        try {
            $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = {$id}";
            $result = $this->db->query($sql);
            return true;
        } catch (Exception $e) {
            throw new Exception('Increment views error: ' . $e->getMessage());
        }
    }
}
