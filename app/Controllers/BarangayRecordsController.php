<?php
namespace App\Controllers;

use App\Models\HealthMetrics;
use App\Models\Barangay;
use App\Models\Individual;
use App\Models\Household;

class BarangayRecordsController {
    private $healthMetricsModel;
    private $barangayModel;
    private $individualModel;
    private $householdModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->healthMetricsModel = new HealthMetrics();
        $this->barangayModel = new Barangay();
        $this->individualModel = new Individual();
        $this->householdModel = new Household();
    }

    public function index() {
        $router = new \Router();
        $barangays = $this->barangayModel->getAll();
        $healthMetrics = $this->healthMetricsModel->getAllBarangayMetrics();
        // Fetch all individuals and households with a high limit (effectively no pagination for charts)
        $individuals = $this->individualModel->getAll(null, 1, 10000);
        $households = $this->householdModel->getAll(null, 1, 10000);
        
        return $router->render('barangay-records.index', [
            'user' => auth_user(),
            'barangays' => $barangays,
            'healthMetrics' => $healthMetrics,
            'individuals' => $individuals,
            'households' => $households,
            'totalBarangays' => count($barangays)
        ]);
    }

    public function getHealthMetrics($params) {
        $barangayId = (int)$params['barangayId'];
        $metrics = $this->healthMetricsModel->getByBarangay($barangayId);
        
        if (!$metrics) {
            $metrics = [
                'immunization_coverage' => 0,
                'maternal_mortality_rate' => 0,
                'infant_mortality_rate' => 0,
                'under5_mortality_rate' => 0
            ];
        }

        return response($metrics, 200);
    }

    public function getMalnutritionData($params) {
        $barangayId = (int)$params['barangayId'];
        $data = $this->healthMetricsModel->getMalnutritionData($barangayId);
        
        if (!$data) {
            $data = [
                'wasting' => 0,
                'stunting' => 0,
                'underweight' => 0
            ];
        }

        return response($data, 200);
    }

    public function getWaterSanitation($params) {
        $barangayId = (int)$params['barangayId'];
        $data = $this->healthMetricsModel->getWaterSanitationAccess($barangayId);
        
        if (!$data) {
            $data = [
                'water_access_percent' => 0,
                'sanitation_access_percent' => 0
            ];
        }

        return response($data, 200);
    }
}
