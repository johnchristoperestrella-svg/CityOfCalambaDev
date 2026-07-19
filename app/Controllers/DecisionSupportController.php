<?php
namespace App\Controllers;

use App\Models\Report;

class DecisionSupportController {
    private $reportModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->reportModel = new Report();
    }

    public function index() {
        $router = new \Router();
        
        // Get real dashboards from database
        $db = new \Database();
        $dashboardQuery = "SELECT 
                            b.name as name, 
                            COUNT(DISTINCT i.id) as reports,
                            MAX(di.import_date) as lastUpdated
                         FROM barangays b
                         LEFT JOIN individuals i ON i.barangay_id = b.id
                         LEFT JOIN data_imports di ON di.id = i.import_id
                         GROUP BY b.id, b.name
                         ORDER BY reports DESC
                         LIMIT 4";
        $dashboardResult = $db->query($dashboardQuery);
        $dashboards = [];
        while($row = $dashboardResult->fetch_assoc()) {
            $dashboards[] = [
                'name' => $row['name'] . ' Dashboard',
                'reports' => (int)$row['reports'],
                'lastUpdated' => $row['lastUpdated'] ? substr($row['lastUpdated'], 0, 10) : date('Y-m-d')
            ];
        }
        
        // Get real reports from database
        $reports = $this->reportModel->getAllPublished(10);
        
        // Format reports for display
        $formattedReports = [];
        foreach($reports as $report) {
            $formattedReports[] = [
                'title' => $report['title'],
                'generated' => substr($report['generated_date'], 0, 10),
                'views' => (int)$report['views'],
                'status' => $report['status']
            ];
        }
        
        return $router->render('decision-support.index', [
            'user' => auth_user(),
            'dashboards' => $dashboards,
            'reports' => $formattedReports,
            'totalDashboards' => count($dashboards),
            'totalReports' => $this->reportModel->getTotalPublishedCount()
        ]);
    }

    public function getDashboards() {
        return response([
            ['name' => 'City Dashboard', 'reports' => 12],
            ['name' => 'Barangay Dashboard', 'reports' => 8],
            ['name' => 'Risk Heatmaps', 'reports' => 5],
            ['name' => 'Policy Simulation', 'reports' => 3]
        ], 200);
    }

    public function getReports() {
        return response([
            ['title' => 'Monthly Progress Report', 'generated' => '2026-04-20', 'views' => 45],
            ['title' => 'Annual Development Goals', 'generated' => '2026-04-15', 'views' => 78],
            ['title' => 'Risk Assessment Report', 'generated' => '2026-04-10', 'views' => 32]
        ], 200);
    }

    public function runPolicySimulation() {
        $simulationParams = $_POST;
        
        $results = [
            'scenario' => $simulationParams['scenario'] ?? 'Default',
            'estimated_impact' => 0.65,
            'population_affected' => 5400,
            'cost_estimate' => 250000
        ];

        return response($results, 200);
    }

    /**
     * Get all analytics data for real-time dashboard
     */
    public function getAnalyticsData() {
        $db = new \Database();
        
        // Records by Barangay
        $barangayQuery = "SELECT b.name as barangay, COUNT(DISTINCT h.id) as households, COUNT(i.id) as individuals 
                         FROM barangays b 
                         LEFT JOIN households h ON h.barangay_id = b.id 
                         LEFT JOIN individuals i ON i.household_id = h.id 
                         GROUP BY b.id, b.name 
                         ORDER BY individuals DESC LIMIT 10";
        $barangayResult = $db->query($barangayQuery);
        $recordsByBarangay = $barangayResult->fetch_all(MYSQLI_ASSOC);
        
        // Import Trend (by month)
        $trendQuery = "SELECT DATE_FORMAT(import_date, '%Y-%m') as month, COUNT(*) as imports, SUM(processed_records) as records 
                      FROM data_imports 
                      WHERE import_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) 
                      GROUP BY DATE_FORMAT(import_date, '%Y-%m') 
                      ORDER BY month ASC";
        $trendResult = $db->query($trendQuery);
        $importTrend = $trendResult->fetch_all(MYSQLI_ASSOC);
        
        // Data Quality Metrics
        $totalIndividuals = $db->query("SELECT COUNT(*) as count FROM individuals")->fetch_assoc()['count'];
        $healthStatusCount = $db->query("SELECT COUNT(*) as count FROM individuals WHERE health_status IS NOT NULL AND health_status != ''")->fetch_assoc()['count'];
        $educationCount = $db->query("SELECT COUNT(*) as count FROM individuals WHERE education_level IS NOT NULL AND education_level != ''")->fetch_assoc()['count'];
        
        $dataQuality = [
            'total_records' => $totalIndividuals,
            'health_data_complete' => $totalIndividuals > 0 ? round(($healthStatusCount / $totalIndividuals) * 100, 1) : 0,
            'education_data_complete' => $totalIndividuals > 0 ? round(($educationCount / $totalIndividuals) * 100, 1) : 0,
            'overall_completeness' => $totalIndividuals > 0 ? round((($healthStatusCount + $educationCount) / ($totalIndividuals * 2)) * 100, 1) : 0
        ];
        
        // Population by Age Group
        $ageQuery = "SELECT 
                    CASE 
                        WHEN age < 5 THEN '0-4'
                        WHEN age < 13 THEN '5-12'
                        WHEN age < 18 THEN '13-17'
                        WHEN age < 30 THEN '18-29'
                        WHEN age < 60 THEN '30-59'
                        ELSE '60+'
                    END as age_group,
                    COUNT(*) as count
                    FROM individuals
                    WHERE age IS NOT NULL
                    GROUP BY age_group
                    ORDER BY FIELD(age_group, '0-4', '5-12', '13-17', '18-29', '30-59', '60+')";
        $ageResult = $db->query($ageQuery);
        $populationByAge = $ageResult->fetch_all(MYSQLI_ASSOC);
        
        return response([
            'success' => true,
            'data' => [
                'records_by_barangay' => $recordsByBarangay,
                'import_trend' => $importTrend,
                'data_quality' => $dataQuality,
                'population_by_age' => $populationByAge
            ]
        ], 200);
    }

    /**
     * Get records by barangay
     */
    public function getRecordsByBarangay() {
        $db = new \Database();
        $query = "SELECT b.name as barangay, COUNT(DISTINCT h.id) as households, COUNT(i.id) as individuals 
                 FROM barangays b 
                 LEFT JOIN households h ON h.barangay_id = b.id 
                 LEFT JOIN individuals i ON i.household_id = h.id 
                 GROUP BY b.id, b.name 
                 ORDER BY individuals DESC LIMIT 10";
        $result = $db->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return response(['success' => true, 'data' => $data], 200);
    }

    /**
     * Get import trend data
     */
    public function getImportTrend() {
        $db = new \Database();
        $query = "SELECT DATE_FORMAT(import_date, '%Y-%m') as month, COUNT(*) as imports, SUM(processed_records) as records 
                 FROM data_imports 
                 WHERE import_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                 GROUP BY DATE_FORMAT(import_date, '%Y-%m') 
                 ORDER BY month ASC";
        $result = $db->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return response(['success' => true, 'data' => $data], 200);
    }

    /**
     * Get data quality metrics
     */
    public function getDataQuality() {
        $db = new \Database();
        
        $totalIndividuals = $db->query("SELECT COUNT(*) as count FROM individuals")->fetch_assoc()['count'];
        $healthStatusCount = $db->query("SELECT COUNT(*) as count FROM individuals WHERE health_status IS NOT NULL AND health_status != ''")->fetch_assoc()['count'];
        $educationCount = $db->query("SELECT COUNT(*) as count FROM individuals WHERE education_level IS NOT NULL AND education_level != ''")->fetch_assoc()['count'];
        
        $data = [
            'total_records' => $totalIndividuals,
            'health_data_complete' => $totalIndividuals > 0 ? round(($healthStatusCount / $totalIndividuals) * 100, 1) : 0,
            'education_data_complete' => $totalIndividuals > 0 ? round(($educationCount / $totalIndividuals) * 100, 1) : 0,
            'overall_completeness' => $totalIndividuals > 0 ? round((($healthStatusCount + $educationCount) / ($totalIndividuals * 2)) * 100, 1) : 0
        ];
        
        return response(['success' => true, 'data' => $data], 200);
    }

    /**
     * Get population distribution by age group
     */
    public function getPopulationByAge() {
        $db = new \Database();
        $query = "SELECT 
                    CASE 
                        WHEN age < 5 THEN '0-4'
                        WHEN age < 13 THEN '5-12'
                        WHEN age < 18 THEN '13-17'
                        WHEN age < 30 THEN '18-29'
                        WHEN age < 60 THEN '30-59'
                        ELSE '60+'
                    END as age_group,
                    COUNT(*) as count
                    FROM individuals
                    WHERE age IS NOT NULL
                    GROUP BY age_group
                    ORDER BY FIELD(age_group, '0-4', '5-12', '13-17', '18-29', '30-59', '60+')";
        $result = $db->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return response(['success' => true, 'data' => $data], 200);
    }
}
