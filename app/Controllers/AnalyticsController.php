<?php
namespace App\Controllers;

use App\Models\Analytics;
use App\Models\DataImport;
use App\Models\Barangay;

class AnalyticsController {
    private $analyticsModel;
    private $importModel;
    private $barangayModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->analyticsModel = new Analytics();
        $this->importModel = new DataImport();
        $this->barangayModel = new Barangay();
    }

    /**
     * Main analytics dashboard
     */
    public function index() {
        $router = new \Router();
        $userRole = auth_role();
        
        // Get analytics based on user role - OPTIMIZED: Single JOIN query instead of N+1
        if ($userRole === 'City Administrator') {
            // For admins: Get all imports with their analytics in ONE query
            $db = new \Database();
            $sql = "SELECT di.*, ia.* 
                    FROM data_imports di
                    LEFT JOIN import_analytics ia ON di.id = ia.import_id
                    ORDER BY di.import_date DESC
                    LIMIT 100";
            $result = $db->query($sql);
            $analytics = $result->fetch_all(MYSQLI_ASSOC);
            $barangays = $this->barangayModel->getAll();
        } else {
            // For others: Get their imports with analytics in ONE query
            $db = new \Database();
            $sql = "SELECT di.*, ia.* 
                    FROM data_imports di
                    LEFT JOIN import_analytics ia ON di.id = ia.import_id
                    WHERE di.user_id = ?
                    ORDER BY di.import_date DESC";
            
            $stmt = $db->prepare($sql);
            $userId = auth_id();
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $analytics = $result->fetch_all(MYSQLI_ASSOC);
            $barangays = [];
        }

        return $router->render('analytics.dashboard', [
            'user' => auth_user(),
            'analytics' => $analytics,
            'barangays' => $barangays,
            'totalAnalytics' => count($analytics)
        ]);
    }

    /**
     * View detailed analytics for a specific import
     */
    public function viewByImport($importId) {
        require_permission('view_analytics');
        
        // Verify user has access to this import
        $import = $this->importModel->getById($importId);
        if (!$import) {
            http_response_code(404);
            return response(['error' => 'Import not found'], 404);
        }

        if (auth_role() !== 'City Administrator' && $import['user_id'] != auth_id()) {
            http_response_code(403);
            return response(['error' => 'Access denied'], 403);
        }

        $analytics = $this->analyticsModel->getByImportId($importId);
        
        if (!$analytics) {
            http_response_code(404);
            return response(['error' => 'Analytics not found for this import'], 404);
        }

        $router = new \Router();
        return $router->render('analytics.view-import', [
            'user' => auth_user(),
            'import' => $import,
            'analytics' => $analytics
        ]);
    }

    /**
     * Get analytics by barangay
     */
    public function viewByBarangay($barangayId) {
        require_permission('view_analytics');
        
        $barangay = $this->barangayModel->getById($barangayId);
        if (!$barangayId) {
            http_response_code(404);
            return response(['error' => 'Barangay not found'], 404);
        }

        $analytics = $this->analyticsModel->getByBarangay($barangayId);
        
        $router = new \Router();
        return $router->render('analytics.view-barangay', [
            'user' => auth_user(),
            'barangay' => $barangay,
            'analytics' => $analytics
        ]);
    }

    /**
     * Compare two imports (API endpoint)
     */
    public function compareImports() {
        require_permission('view_analytics');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $importId1 = $data['import_id_1'] ?? null;
        $importId2 = $data['import_id_2'] ?? null;
        $barangayId = $data['barangay_id'] ?? null;

        if (!$importId1 || !$importId2 || !$barangayId) {
            http_response_code(400);
            return response(['error' => 'Missing required parameters'], 400);
        }

        $import1 = $this->importModel->getById($importId1);
        $import2 = $this->importModel->getById($importId2);

        if (!$import1 || !$import2) {
            http_response_code(404);
            return response(['error' => 'One or both imports not found'], 404);
        }

        if (auth_role() !== 'City Administrator') {
            if ($import1['user_id'] != auth_id() || $import2['user_id'] != auth_id()) {
                http_response_code(403);
                return response(['error' => 'Access denied'], 403);
            }
        }

        $comparison = $this->analyticsModel->compareImports($importId1, $importId2, $barangayId);

        if (!$comparison) {
            http_response_code(404);
            return response(['error' => 'Could not generate comparison'], 404);
        }

        return response([
            'success' => true,
            'data' => $comparison,
            'import_1' => $import1,
            'import_2' => $import2
        ], 200);
    }

    /**
     * Get analytics summary (API endpoint)
     */
    public function getSummary() {
        require_permission('view_analytics');

        $userRole = auth_role();
        
        if ($userRole === 'City Administrator') {
            $analytics = $this->analyticsModel->getAll(100);
        } else {
            $imports = $this->importModel->getByUser(auth_id());
            $importIds = array_column($imports, 'id');
            $analytics = [];
            foreach ($importIds as $id) {
                $analytic = $this->analyticsModel->getByImportId($id);
                if ($analytic) {
                    $analytics[] = $analytic;
                }
            }
        }

        $summary = $this->calculateSummary($analytics);

        return response([
            'success' => true,
            'summary' => $summary,
            'total_imports_analyzed' => count($analytics)
        ], 200);
    }

    /**
     * Calculate overall summary from multiple analytics
     */
    private function calculateSummary($analytics) {
        if (empty($analytics)) {
            return [
                'total_households' => 0,
                'total_individuals' => 0,
                'average_household_size' => 0,
                'total_low_income_households' => 0,
                'total_at_risk_individuals' => 0
            ];
        }

        $totalHouseholds = 0;
        $totalIndividuals = 0;
        $avgHouseholdSizes = [];
        $totalLowIncome = 0;
        $totalAtRisk = 0;

        foreach ($analytics as $analytic) {
            $totalHouseholds += $analytic['total_households'];
            $totalIndividuals += $analytic['total_individuals'];
            $avgHouseholdSizes[] = $analytic['average_household_size'];
            $totalLowIncome += $analytic['low_income_households'];
            $totalAtRisk += $analytic['health_at_risk_count'];
        }

        $avgSize = !empty($avgHouseholdSizes) ? round(array_sum($avgHouseholdSizes) / count($avgHouseholdSizes), 2) : 0;

        return [
            'total_households' => $totalHouseholds,
            'total_individuals' => $totalIndividuals,
            'average_household_size' => $avgSize,
            'total_low_income_households' => $totalLowIncome,
            'total_at_risk_individuals' => $totalAtRisk,
            'low_income_percentage' => $totalHouseholds > 0 ? round(($totalLowIncome / $totalHouseholds) * 100, 2) : 0,
            'at_risk_percentage' => $totalIndividuals > 0 ? round(($totalAtRisk / $totalIndividuals) * 100, 2) : 0
        ];
    }

    /**
     * Export analytics as PDF or CSV (API endpoint)
     */
    public function exportAnalytics() {
        require_permission('view_analytics');

        $importId = $_GET['import_id'] ?? null;
        $format = $_GET['format'] ?? 'json';

        if (!$importId) {
            http_response_code(400);
            return response(['error' => 'Import ID is required'], 400);
        }

        $import = $this->importModel->getById($importId);
        if (!$import) {
            http_response_code(404);
            return response(['error' => 'Import not found'], 404);
        }

        if (auth_role() !== 'City Administrator' && $import['user_id'] != auth_id()) {
            http_response_code(403);
            return response(['error' => 'Access denied'], 403);
        }

        $analytics = $this->analyticsModel->getByImportId($importId);

        if (!$analytics) {
            http_response_code(404);
            return response(['error' => 'Analytics not found'], 404);
        }

        switch ($format) {
            case 'csv':
                return $this->exportAsCSV($import, $analytics);
            case 'json':
            default:
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="analytics_' . $importId . '.json"');
                echo json_encode([
                    'import' => $import,
                    'analytics' => $analytics
                ], JSON_PRETTY_PRINT);
                exit;
        }
    }

    /**
     * Export analytics as CSV
     */
    private function exportAsCSV($import, $analytics) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="analytics_' . $import['id'] . '.csv"');
        
        $output = fopen('php://output', 'w');

        // Header information
        fputcsv($output, ['Import Analytics Report']);
        fputcsv($output, ['File', $import['file_name']]);
        fputcsv($output, ['Date', $import['import_date']]);
        fputcsv($output, []);

        // Analytics data
        fputcsv($output, ['Metric', 'Value']);
        fputcsv($output, ['Total Records', $analytics['total_records']]);
        fputcsv($output, ['Total Households', $analytics['total_households']]);
        fputcsv($output, ['Total Individuals', $analytics['total_individuals']]);
        fputcsv($output, ['Average Household Size', $analytics['average_household_size']]);
        fputcsv($output, ['Average Age', $analytics['average_age']]);
        fputcsv($output, ['Low Income Households', $analytics['low_income_households']]);
        fputcsv($output, ['Low Income Percentage', $analytics['low_income_percentage'] . '%']);
        fputcsv($output, ['At-Risk Individuals', $analytics['health_at_risk_count']]);
        fputcsv($output, ['At-Risk Percentage', $analytics['health_at_risk_percentage'] . '%']);
        fputcsv($output, []);

        // Gender Distribution
        fputcsv($output, ['Gender Distribution']);
        fputcsv($output, ['Category', 'Count', 'Percentage']);
        foreach ($analytics['gender_distribution'] as $gender => $data) {
            fputcsv($output, [$gender, $data['count'], $data['percentage'] . '%']);
        }
        fputcsv($output, []);

        // Education Distribution
        fputcsv($output, ['Education Distribution']);
        fputcsv($output, ['Category', 'Count', 'Percentage']);
        foreach ($analytics['education_distribution'] as $education => $data) {
            fputcsv($output, [$education, $data['count'], $data['percentage'] . '%']);
        }
        fputcsv($output, []);

        // Health Status Distribution
        fputcsv($output, ['Health Status Distribution']);
        fputcsv($output, ['Category', 'Count', 'Percentage']);
        foreach ($analytics['health_status_distribution'] as $health => $data) {
            fputcsv($output, [$health, $data['count'], $data['percentage'] . '%']);
        }
        fputcsv($output, []);

        // Socioeconomic Distribution
        fputcsv($output, ['Socioeconomic Distribution']);
        fputcsv($output, ['Category', 'Count', 'Percentage']);
        foreach ($analytics['socioeconomic_distribution'] as $status => $data) {
            fputcsv($output, [$status, $data['count'], $data['percentage'] . '%']);
        }
        fputcsv($output, []);

        // Key Findings
        fputcsv($output, ['Key Findings']);
        fputcsv($output, [$analytics['key_findings']]);
        fputcsv($output, []);

        // Recommendations
        fputcsv($output, ['Recommendations']);
        fputcsv($output, [$analytics['recommendations']]);

        fclose($output);
        exit;
    }

    /**
     * Get analytics metrics endpoint (for charts)
     */
    public function getMetrics($importId) {
        require_permission('view_analytics');

        $analytics = $this->analyticsModel->getByImportId($importId);

        if (!$analytics) {
            http_response_code(404);
            return response(['error' => 'Analytics not found'], 404);
        }

        return response([
            'success' => true,
            'metrics' => [
                'gender' => $analytics['gender_distribution'],
                'education' => $analytics['education_distribution'],
                'health_status' => $analytics['health_status_distribution'],
                'socioeconomic' => $analytics['socioeconomic_distribution']
            ]
        ], 200);
    }
}
