<?php
namespace App\Models;

class HealthMetrics {
    private $db;
    private $table = 'health_metrics';

    public function __construct() {
        $this->db = new \Database();
    }

    /**
     * Get latest health metrics for barangay with type-safe parameter
     */
    public function getByBarangay($barangayId) {
        $barangayId = (int)$barangayId;
        
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE barangay_id = {$barangayId} 
                    ORDER BY recorded_date DESC 
                    LIMIT 1";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get by barangay error: ' . $e->getMessage());
        }
    }

    /**
     * Get all barangay health metrics
     */
    public function getAllBarangayMetrics() {
        try {
            $sql = "SELECT b.id, b.name, 
                        COALESCE(hm.immunization_coverage, 0) as immunization_coverage,
                        COALESCE(hm.maternal_mortality_rate, 0) as maternal_mortality_rate,
                        COALESCE(hm.infant_mortality_rate, 0) as infant_mortality_rate,
                        COALESCE(hm.under5_mortality_rate, 0) as under5_mortality_rate
                    FROM barangays b
                    LEFT JOIN (
                        SELECT barangay_id, MAX(id) as latest_id 
                        FROM {$this->table} 
                        GROUP BY barangay_id
                    ) hm_latest ON b.id = hm_latest.barangay_id
                    LEFT JOIN {$this->table} hm ON hm.id = hm_latest.latest_id
                    ORDER BY b.name";
            $result = $this->db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Get all metrics error: ' . $e->getMessage());
        }
    }

    /**
     * Get malnutrition data with type-safe parameter
     */
    public function getMalnutritionData($barangayId) {
        $barangayId = (int)$barangayId;
        
        try {
            $sql = "SELECT wasting, stunting, underweight FROM {$this->table} 
                    WHERE barangay_id = {$barangayId} 
                    ORDER BY recorded_date DESC 
                    LIMIT 1";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get malnutrition data error: ' . $e->getMessage());
        }
    }

    /**
     * Get water sanitation access with type-safe parameter
     */
    public function getWaterSanitationAccess($barangayId) {
        $barangayId = (int)$barangayId;
        
        try {
            $sql = "SELECT water_access_percent, sanitation_access_percent FROM {$this->table} 
                    WHERE barangay_id = {$barangayId} 
                    ORDER BY recorded_date DESC 
                    LIMIT 1";
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception('Get water sanitation error: ' . $e->getMessage());
        }
    }

    /**
     * Create health metrics record with prepared statement
     */
    public function create($data) {
        try {
            return $this->db->executeInsert($this->table, [
                'barangay_id' => (int)$data['barangay_id'],
                'immunization_coverage' => (float)($data['immunization_coverage'] ?? 0),
                'maternal_mortality_rate' => (float)($data['maternal_mortality_rate'] ?? 0),
                'infant_mortality_rate' => (float)($data['infant_mortality_rate'] ?? 0),
                'under5_mortality_rate' => (float)($data['under5_mortality_rate'] ?? 0),
                'wasting' => (float)($data['wasting'] ?? 0),
                'stunting' => (float)($data['stunting'] ?? 0),
                'underweight' => (float)($data['underweight'] ?? 0),
                'water_access_percent' => (float)($data['water_access_percent'] ?? 0),
                'sanitation_access_percent' => (float)($data['sanitation_access_percent'] ?? 0),
                'recorded_date' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            throw new Exception('Create health metrics error: ' . $e->getMessage());
        }
    }
}
