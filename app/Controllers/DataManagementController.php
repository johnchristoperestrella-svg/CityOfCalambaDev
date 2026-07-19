<?php
namespace App\Controllers;

use App\Models\Barangay;
use App\Models\Household;
use App\Models\Individual;
use App\Models\AuditLog;

class DataManagementController {
    private $barangayModel;
    private $householdModel;
    private $individualModel;
    private $auditLog;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->barangayModel = new Barangay();
        $this->householdModel = new Household();
        $this->individualModel = new Individual();
        $this->auditLog = new AuditLog();
    }

    public function index() {
        $router = new \Router();
        $barangays = $this->barangayModel->getAll();
        $households = $this->householdModel->getAll();
        $individuals = $this->individualModel->getAll();
        
        return $router->render('data-management.index', [
            'user' => auth_user(),
            'barangays' => $barangays,
            'households' => $households,
            'individuals' => $individuals,
            'totalBarangays' => count($barangays),
            'totalHouseholds' => $this->householdModel->getTotalCount(),
            'totalIndividuals' => $this->individualModel->getTotalCount()
        ]);
    }

    public function getBarangays() {
        return response($this->barangayModel->getAll(), 200);
    }

    public function getHouseholds($params = []) {
        $barangayId = $_GET['barangay_id'] ?? null;
        $households = $this->householdModel->getAll($barangayId);
        return response($households, 200);
    }

    public function getIndividuals($params = []) {
        $barangayId = $_GET['barangay_id'] ?? null;
        $individuals = $this->individualModel->getAll($barangayId);
        return response($individuals, 200);
    }

    public function getBarangayMembers($params = []) {
        $barangayId = (int)($params['barangayId'] ?? 0);
        
        if ($barangayId <= 0) {
            http_response_code(400);
            return response(['error' => 'Invalid barangay ID', 'members' => []], 400);
        }

        // Get individuals from this barangay
        $members = $this->individualModel->getByBarangay($barangayId);
        
        return response(['members' => $members ?? [], 'total' => count($members ?? [])], 200);
    }

    public function getDataQuality() {
        $barangays = $this->barangayModel->getAll();
        $qualityMetrics = [
            'total_records' => count($barangays) * 3,
            'complete_records' => count($barangays) * 2.7,
            'accuracy_rate' => 95.5,
            'last_updated' => date('Y-m-d H:i:s')
        ];
        return response($qualityMetrics, 200);
    }

    public function createBarangay() {
        if (!has_role('POPDEV Manager')) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        $data = [
            'name' => sanitize_input($_POST['name'] ?? ''),
            'population' => (int)($_POST['population'] ?? 0),
            'area' => (float)($_POST['area'] ?? 0),
            'chairman' => sanitize_input($_POST['chairman'] ?? ''),
            'contact' => sanitize_input($_POST['contact'] ?? '')
        ];

        if (empty($data['name']) || $data['population'] <= 0) {
            http_response_code(400);
            return response(['error' => 'Invalid data'], 400);
        }

        if ($this->barangayModel->create($data)) {
            $this->auditLog->log('CREATE_BARANGAY', "Created barangay: {$data['name']}", auth_id());
            return response(['success' => true, 'message' => 'Barangay created'], 201);
        }

        http_response_code(500);
        return response(['error' => 'Failed to create barangay'], 500);
    }

    public function updateBarangay($params) {
        $id = (int)$params['id'];
        $data = [
            'name' => sanitize_input($_POST['name'] ?? ''),
            'population' => (int)($_POST['population'] ?? 0),
            'area' => (float)($_POST['area'] ?? 0),
            'chairman' => sanitize_input($_POST['chairman'] ?? ''),
            'contact' => sanitize_input($_POST['contact'] ?? '')
        ];

        if ($this->barangayModel->update($id, $data)) {
            $this->auditLog->log('UPDATE_BARANGAY', "Updated barangay ID: {$id}", auth_id());
            return response(['success' => true], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to update barangay'], 500);
    }

    public function deleteBarangay($params) {
        if (!is_super_admin()) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        $id = (int)$params['id'];

        if ($this->barangayModel->delete($id)) {
            $this->auditLog->log('DELETE_BARANGAY', "Deleted barangay ID: {$id}", auth_id());
            return response(['success' => true], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to delete barangay'], 500);
    }
}
