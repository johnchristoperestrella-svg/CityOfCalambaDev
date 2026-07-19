<?php
namespace App\Models;

class Report {
    private $db;
    private $table = 'reports';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get all published reports
     */
    public function getAllPublished($limit = 100) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE status = 'published'
                    ORDER BY published_date DESC
                    LIMIT {$limit}";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } catch (Exception $e) {
            throw new Exception('Get published reports error: ' . $e->getMessage());
        }
    }

    /**
     * Get total published reports count
     */
    public function getTotalPublishedCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE status = 'published'";
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            return (int)$row['cnt'];
        } catch (Exception $e) {
            throw new Exception('Get count error: ' . $e->getMessage());
        }
    }

    /**
     * Get report by ID
     */
    public function getById($id) {
        $id = (int)$id;
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get by ID error: ' . $e->getMessage());
        }
    }

    /**
     * Get reports by type
     */
    public function getByType($type, $limit = 50) {
        try {
            $type = $this->db->escape_string($type);
            $sql = "SELECT * FROM {$this->table} 
                    WHERE status = 'published' AND type = '{$type}'
                    ORDER BY published_date DESC
                    LIMIT {$limit}";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } catch (Exception $e) {
            throw new Exception('Get by type error: ' . $e->getMessage());
        }
    }

    /**
     * Create report
     */
    public function create($data) {
        try {
            $id = $this->db->executeInsert($this->table, [
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'type' => $data['type'] ?? 'other',
                'generated_date' => $data['generated_date'] ?? date('Y-m-d H:i:s'),
                'published_date' => $data['published_date'] ?? null,
                'published_by' => isset($data['published_by']) ? (int)$data['published_by'] : null,
                'views' => (int)($data['views'] ?? 0),
                'status' => $data['status'] ?? 'draft',
                'content_path' => $data['content_path'] ?? null,
                'import_id' => isset($data['import_id']) ? (int)$data['import_id'] : null
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Create report error: ' . $e->getMessage());
        }
    }

    /**
     * Update report views
     */
    public function incrementViews($id) {
        $id = (int)$id;
        try {
            $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = {$id}";
            return $this->db->query($sql);
        } catch (Exception $e) {
            throw new Exception('Increment views error: ' . $e->getMessage());
        }
    }

    /**
     * Publish report
     */
    public function publish($id, $publishedBy) {
        $id = (int)$id;
        $publishedBy = (int)$publishedBy;
        
        try {
            $sql = "UPDATE {$this->table} 
                    SET status = 'published', 
                        published_date = NOW(), 
                        published_by = {$publishedBy}
                    WHERE id = {$id}";
            return $this->db->query($sql);
        } catch (Exception $e) {
            throw new Exception('Publish report error: ' . $e->getMessage());
        }
    }

    /**
     * Archive report
     */
    public function archive($id) {
        $id = (int)$id;
        try {
            $sql = "UPDATE {$this->table} SET status = 'archived' WHERE id = {$id}";
            return $this->db->query($sql);
        } catch (Exception $e) {
            throw new Exception('Archive report error: ' . $e->getMessage());
        }
    }

    /**
     * Get latest reports by type
     */
    public function getLatestByType($limit = 5) {
        try {
            $sql = "SELECT DISTINCT type, COUNT(*) as count, MAX(published_date) as latest_date
                    FROM {$this->table}
                    WHERE status = 'published'
                    GROUP BY type
                    ORDER BY latest_date DESC
                    LIMIT {$limit}";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } catch (Exception $e) {
            throw new Exception('Get latest by type error: ' . $e->getMessage());
        }
    }
}
?>
