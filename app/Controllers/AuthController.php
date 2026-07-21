<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\AuditLog;

class AuthController {
    private $userModel;
    private $auditLog;

    public function __construct() {
        $this->userModel = new User();
        $this->auditLog = new AuditLog();
    }

    public function login() {
        if (is_authenticated()) {
            redirect('/dashboard');
        }
        $router = new \Router();
        return $router->render('auth.login');
    }

    public function handleLogin($params = []) {
        session_start_custom();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!validate_email($email) || empty($password)) {
            http_response_code(400);
            return response(['error' => 'Invalid email or password'], 400);
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            // Audit logging must never prevent the user from receiving the
            // appropriate authentication response.
            try {
                $this->auditLog->log('LOGIN_FAILED', "Failed login attempt for {$email}", null);
            } catch (\Throwable $e) {
                error_log('Unable to write failed-login audit entry: ' . $e->getMessage());
            }
            http_response_code(401);
            return response(['error' => 'Invalid credentials'], 401);
        }

        if ($user['status'] !== 'active') {
            http_response_code(403);
            return response(['error' => 'Account is inactive'], 403);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['user_role'] = $user['role'];

        try {
            $this->auditLog->log('LOGIN_SUCCESS', "User {$user['email']} logged in", $user['id']);
        } catch (\Throwable $e) {
            error_log('Unable to write successful-login audit entry: ' . $e->getMessage());
        }

        return response(['success' => true, 'redirect' => url('/dashboard')], 200);
    }

    public function register($params = []) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Show register page
            if (is_authenticated()) {
                redirect('/dashboard');
            }
            $router = new \Router();
            return $router->render('auth.register');
        }

        // Handle POST request for registration
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        // Handle both JSON and form POST data
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            // Parse JSON request
            $data = json_decode(file_get_contents('php://input'), true);
            $email = sanitize_input($data['email'] ?? '');
            $password = $data['password'] ?? '';
            $confirmPassword = $data['password_confirm'] ?? '';
            $name = sanitize_input($data['name'] ?? '');
        } else {
            // Parse form data
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $name = sanitize_input($_POST['name'] ?? '');
        }

        if (!validate_email($email) || empty($password) || empty($name)) {
            http_response_code(400);
            return response(['error' => 'All fields are required'], 400);
        }

        // Validate that email is from @calamba.gov.ph domain
        if (!str_ends_with(strtolower($email), '@calamba.gov.ph')) {
            http_response_code(400);
            return response(['error' => 'Email must be from the @calamba.gov.ph domain'], 400);
        }

        if ($password !== $confirmPassword) {
            http_response_code(400);
            return response(['error' => 'Passwords do not match'], 400);
        }

        if (strlen($password) < 8) {
            http_response_code(400);
            return response(['error' => 'Password must be at least 8 characters'], 400);
        }

        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            http_response_code(409);
            return response(['error' => 'Email already registered'], 409);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $success = $this->userModel->create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'role' => 'Analyst'
        ]);

        // Attempt to save to Firebase if library is available
        try {
            if (class_exists('Kreait\\Firebase\\Factory')) {
                $firebaseConfig = require base_path('config/firebase.php');
                $factory = (new \Kreait\Firebase\Factory())
                    ->withServiceAccount($firebaseConfig['service_account'])
                    ->withDatabaseUri($firebaseConfig['database_url']);
                $database = $factory->createDatabase();
                $database->getReference('users')->push([
                    'email' => $email,
                    'name' => $name,
                    'role' => 'Analyst',
                    'created_at' => date('c')
                ]);
            }
        } catch (\Throwable $e) {
            // Log or ignore Firebase errors, do not block registration
        }

        if ($success) {
            // Auto-login after registration
            session_start_custom();
            $user = $this->userModel->findByEmail($email);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;
            $_SESSION['user_role'] = $user['role'];
            
            // Log registration with correct user ID
            $this->auditLog->log('USER_REGISTERED', "New user registered: {$email}", $user['id']);
            
            return response(['success' => true, 'redirect' => url('/dashboard')], 201);
        }

        http_response_code(500);
        return response(['error' => 'Registration failed'], 500);
    }

    public function logout() {
        session_start_custom();
        if (isset($_SESSION['user_id'])) {
            $this->auditLog->log('LOGOUT', 'User logged out', $_SESSION['user_id']);
        }
        
        session_destroy();
        redirect('/');
    }

    public function getProfile() {
        if (!is_authenticated()) {
            http_response_code(401);
            return response(['error' => 'Not authenticated'], 401);
        }

        $user = auth_user();
        return response([
            'id' => auth_id(),
            'email' => $user['email'] ?? null,
            'name' => $user['name'] ?? null,
            'role' => $user['role'] ?? null,
            'barangay_id' => $_SESSION['user_barangay_id'] ?? null,
            'barangay_name' => $_SESSION['user_barangay_name'] ?? null
        ], 200);
    }

    public function checkEmail() {
        $email = sanitize_input($_GET['email'] ?? '');
        if (!validate_email($email)) {
            return response(['error' => 'Invalid email'], 400);
        }

        $existing = $this->userModel->findByEmail($email);
        return response(['exists' => (bool) $existing], 200);
    }
}
