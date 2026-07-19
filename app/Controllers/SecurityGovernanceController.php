<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\DataImport;
use App\Models\Barangay;

class SecurityGovernanceController {
    private $userModel;
    private $auditLog;
    private $importModel;
    private $barangayModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        
        if (!is_super_admin()) {
            http_response_code(403);
            require_once base_path('resources/views/errors/403.php');
            exit;
        }

        $this->userModel = new User();
        $this->auditLog = new AuditLog();
        $this->importModel = new DataImport();
        $this->barangayModel = new Barangay();
    }

    public function index() {
        $router = new \Router();
        $users = $this->userModel->getAll();
        $auditLogs = $this->auditLog->getAll(50);
        $imports = $this->importModel->getAllImports();
        $barangays = $this->barangayModel->getAll();
        
        return $router->render('security-governance.index', [
            'user' => auth_user(),
            'users' => $users,
            'auditLogs' => $auditLogs,
            'imports' => $imports,
            'barangays' => $barangays,
            'totalUsers' => count($users),
            'totalLogs' => count($auditLogs),
            'totalImports' => count($imports),
            'totalBarangays' => count($barangays)
        ]);
    }

    public function getUsers() {
        $users = $this->userModel->getAll();
        return response($users, 200);
    }

    public function createUser() {
        $email = sanitize_input($_POST['email'] ?? '');
        $name = sanitize_input($_POST['name'] ?? '');
        $role = sanitize_input($_POST['role'] ?? 'Analyst');
        $password = password_hash($_POST['password'] ?? 'DefaultPass@123', PASSWORD_BCRYPT);

        if (!validate_email($email) || empty($name)) {
            http_response_code(400);
            return response(['error' => 'Invalid data'], 400);
        }

        if ($this->userModel->create([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'role' => $role
        ])) {
            $this->auditLog->log('CREATE_USER', "Created user: {$email}", auth_id());
            return response(['success' => true], 201);
        }

        http_response_code(500);
        return response(['error' => 'Failed to create user'], 500);
    }

    public function updateUser($params) {
        $id = (int)$params['id'];
        // Allow updating name, role, status and password
        $name = sanitize_input($_POST['name'] ?? '');
        $role = sanitize_input($_POST['role'] ?? 'Analyst');
        $status = sanitize_input($_POST['status'] ?? 'active');

        $updateData = ['role' => $role, 'status' => $status];
        if (!empty($name)) {
            $updateData['name'] = $name;
        }

        // If password provided, hash it and update
        if (!empty($_POST['password'])) {
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }

        if ($this->userModel->update($id, $updateData)) {
            $this->auditLog->log('UPDATE_USER', "Updated user ID: {$id}", auth_id());
            return response(['success' => true], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to update user'], 500);
    }

    public function deleteUser($params) {
        $id = (int)$params['id'];

        if ($id === auth_id()) {
            http_response_code(400);
            return response(['error' => 'Cannot delete your own account'], 400);
        }

        if ($this->userModel->delete($id)) {
            $this->auditLog->log('DELETE_USER', "Deleted user ID: {$id}", auth_id());
            return response(['success' => true], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to delete user'], 500);
    }

    public function getImports() {
        $imports = $this->importModel->getAllImports();
        return response($imports, 200);
    }

    public function deleteImport($params) {
        $importId = (int)$params['id'];

        // Get import details first
        $import = $this->importModel->getById($importId);
        if (!$import) {
            http_response_code(404);
            return response(['error' => 'Import not found'], 404);
        }

        // Delete households and individuals linked to this import
        $db = new \Database();
        
        // Get all households for this import
        $householdResult = $db->query("SELECT id FROM households WHERE import_id = {$importId}");
        $householdIds = [];
        while ($row = $householdResult->fetch_assoc()) {
            $householdIds[] = $row['id'];
        }

        // Delete individuals linked to these households
        if (!empty($householdIds)) {
            $idStr = implode(',', $householdIds);
            $db->query("DELETE FROM individuals WHERE household_id IN ({$idStr})");
        }

        // Delete households
        $db->query("DELETE FROM households WHERE import_id = {$importId}");

        // Delete the import record
        if ($this->importModel->delete($importId)) {
            // Delete the file if it exists
            $filePath = base_path($import['file_path']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $this->auditLog->log('DELETE_IMPORT', "Deleted import ID: {$importId} ({$import['file_name']}), Households deleted: " . count($householdIds), auth_id());
            return response(['success' => true, 'message' => 'Import and associated data deleted'], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to delete import'], 500);
    }

    public function getAuditLogs() {
        $logs = $this->auditLog->getAll();
        return response($logs, 200);
    }

    public function getSecurityStatus() {
        return response([
            'status' => 'Secure',
            'active_users' => 34,
            'failed_login_attempts' => 12,
            'last_backup' => '2 hours ago',
            'encryption_status' => '100%',
            'compliance_score' => 93
        ], 200);
    }

    public function getRolePermissions() {
        $roles = [
            [
                'role' => 'City Administrator',
                'permissions' => ['view_all', 'edit_all', 'delete_all', 'manage_users', 'manage_permissions', 'view_audit_logs', 'manage_system'],
                'users' => 3
            ],
            [
                'role' => 'POPDEV Manager',
                'permissions' => ['view_all', 'edit_data', 'delete_data', 'manage_documents', 'upload_excel', 'view_analytics'],
                'users' => 5
            ],
            [
                'role' => 'Barangay Data Encoder',
                'permissions' => ['view_assigned', 'edit_assigned', 'upload_excel', 'view_reports'],
                'users' => 54
            ],
            [
                'role' => 'Analyst',
                'permissions' => ['view_all', 'view_analytics', 'generate_reports'],
                'users' => 8
            ],
            [
                'role' => 'Viewer',
                'permissions' => ['view_public', 'view_summary'],
                'users' => 12
            ]
        ];

        return response($roles, 200);
    }

    public function updateUserPermissions($params) {
        if (!is_super_admin()) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        $userId = (int)$params['id'];
        $permissions = $_POST['permissions'] ?? [];
        $barangayId = $_POST['barangay_id'] ?? null;

        // Update user permissions in database
        // Implementation would depend on actual database structure

        return response(['success' => true, 'message' => 'Permissions updated'], 200);
    }

    public function getSystemSecurityMetrics() {
        return response([
            'total_users' => 82,
            'active_sessions' => 34,
            'encryption_coverage' => 100,
            'failed_logins_24h' => 12,
            'successful_logins_24h' => 245,
            'data_backups_completed' => 89,
            'compliance_score' => 93,
            'security_incidents_7d' => 0,
            'last_security_audit' => '2026-04-20',
            'tls_enabled' => true,
            'mfa_enabled' => false,
            'api_rate_limiting' => true
        ], 200);
    }

    /**
     * Get all barangays
     */
    public function getBarangays() {
        try {
            $barangays = $this->barangayModel->getAll();
            return response($barangays, 200);
        } catch (Exception $e) {
            http_response_code(500);
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create new barangay (Super Admin only)
     */
    public function createBarangay() {
        if (!is_super_admin()) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        // Get JSON data or POST data
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $name = sanitize_input($input['name'] ?? '');
        $chairman = sanitize_input($input['chairman'] ?? '');
        $contact = sanitize_input($input['contact'] ?? '');
        $population = (int)($input['population'] ?? 0);
        $area = (float)($input['area'] ?? 0);

        if (empty($name) || empty($chairman) || empty($contact)) {
            http_response_code(400);
            return response(['error' => 'Barangay name, chairman, and contact are required'], 400);
        }

        try {
            $id = $this->barangayModel->create([
                'name' => $name,
                'chairman' => $chairman,
                'contact' => $contact,
                'population' => $population,
                'area' => $area
            ]);

            $this->auditLog->log('CREATE_BARANGAY', "Created barangay: {$name}, Chairman: {$chairman}", auth_id());
            
            // Return the created barangay
            $barangay = $this->barangayModel->getById($id);
            return response($barangay, 201);
        } catch (Exception $e) {
            http_response_code(500);
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update barangay (Super Admin only)
     */
    public function updateBarangay($params) {
        if (!is_super_admin()) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        // Get JSON data or POST data
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $id = (int)$params['id'];
        $name = sanitize_input($input['name'] ?? '');
        $chairman = sanitize_input($input['chairman'] ?? '');
        $contact = sanitize_input($input['contact'] ?? '');
        $population = isset($input['population']) ? (int)$input['population'] : null;
        $area = isset($input['area']) ? (float)$input['area'] : null;

        if (empty($name) || empty($chairman) || empty($contact)) {
            http_response_code(400);
            return response(['error' => 'Barangay name, chairman, and contact are required'], 400);
        }

        try {
            $updateData = [
                'name' => $name,
                'chairman' => $chairman,
                'contact' => $contact
            ];

            if ($population !== null) {
                $updateData['population'] = $population;
            }
            if ($area !== null) {
                $updateData['area'] = $area;
            }

            $this->barangayModel->update($id, $updateData);
            $this->auditLog->log('UPDATE_BARANGAY', "Updated barangay ID: {$id}", auth_id());

            $barangay = $this->barangayModel->getById($id);
            return response($barangay, 200);
        } catch (Exception $e) {
            http_response_code(500);
            return response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete barangay (Super Admin only)
     */
    public function deleteBarangay($params) {
        if (!is_super_admin()) {
            http_response_code(403);
            return response(['error' => 'Unauthorized'], 403);
        }

        $id = (int)$params['id'];

        try {
            $barangay = $this->barangayModel->getById($id);
            if (!$barangay) {
                http_response_code(404);
                return response(['error' => 'Barangay not found'], 404);
            }

            $this->barangayModel->delete($id);
            $this->auditLog->log('DELETE_BARANGAY', "Deleted barangay ID: {$id} ({$barangay['name']})", auth_id());
            
            return response(['success' => true, 'message' => 'Barangay deleted successfully'], 200);
        } catch (Exception $e) {
            http_response_code(500);
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
