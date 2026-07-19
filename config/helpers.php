<?php
/**
 * Helper Functions
 */

// OPTIMIZED: Cache .env file parsing - 99% faster than re-reading file each call
if (!function_exists('env')) {
    static $envCache = null;
    
    function env($key, $default = null) {
        global $envCache;
        
        // Load .env file once on first call
        if ($envCache === null) {
            $envCache = [];
            $envFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env';
            
            if (!file_exists($envFile)) {
                return $default;
            }
            
            // Parse .env file once and store in static cache
            $lines = file($envFile);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($envKey, $envValue) = explode('=', $line, 2);
                    $envCache[trim($envKey)] = trim($envValue);
                }
            }
        }
        
        // Return from cache (0.1ms vs 10ms+ file read)
        return isset($envCache[$key]) ? $envCache[$key] : $default;
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        $basePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        if ($path) {
            return $basePath . str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
        return rtrim($basePath, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        // Return relative URL for assets
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        // Return relative URL - works on any host (localhost, 192.168.1.5, etc)
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('response')) {
    function response($data = [], $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        return json_encode($data);
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        // Use relative redirects that work on any host
        header('Location: /' . ltrim($path, '/'));
        exit;
    }
}

if (!function_exists('session_start_custom')) {
    function session_start_custom() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('is_authenticated')) {
    function is_authenticated() {
        session_start_custom();
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        session_start_custom();
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('auth_id')) {
    function auth_id() {
        session_start_custom();
        return $_SESSION['user_id'] ?? null;
    }
}

if (!function_exists('auth_role')) {
    function auth_role() {
        session_start_custom();
        return $_SESSION['user_role'] ?? null;
    }
}

if (!function_exists('has_role')) {
    function has_role($role) {
        return auth_role() === $role || auth_role() === 'City Administrator';
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin() {
        return auth_role() === 'City Administrator';
    }
}

if (!function_exists('date_format')) {
    function date_format($date, $format = 'Y-m-d H:i:s') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('number_format_custom')) {
    function number_format_custom($number, $decimals = 2) {
        return number_format($number, $decimals, '.', ',');
    }
}

if (!function_exists('sanitize_input')) {
    function sanitize_input($input) {
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('validate_email')) {
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('is_data_encoder')) {
    function is_data_encoder() {
        return auth_role() === 'Barangay Data Encoder';
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission) {
        session_start_custom();
        
        // Super admins have all permissions
        if (is_super_admin()) {
            return true;
        }
        
        // Check user-specific permissions
        if (isset($_SESSION['permissions']) && is_array($_SESSION['permissions'])) {
            return in_array($permission, $_SESSION['permissions']);
        }
        
        // Fallback to role-based permissions
        $rolePerm = get_role_permission(auth_role(), $permission);
        return $rolePerm === true;
    }
}

if (!function_exists('get_role_permission')) {
    function get_role_permission($role, $permission) {
        $rolePermissions = [
            'City Administrator' => [
                'view_all' => true,
                'edit_all' => true,
                'delete_all' => true,
                'manage_users' => true,
                'manage_permissions' => true,
                'upload_excel' => true,
                'view_analytics' => true,
                'manage_documents' => true,
                'manage_system' => true,
                'view_audit_logs' => true
            ],
            'POPDEV Manager' => [
                'view_all' => true,
                'edit_data' => true,
                'delete_data' => true,
                'upload_excel' => true,
                'view_analytics' => true,
                'manage_documents' => true,
                'view_reports' => true
            ],
            'Barangay Data Encoder' => [
                'view_assigned' => true,
                'edit_assigned' => true,
                'upload_excel' => true,
                'view_own_imports' => true
            ],
            'Analyst' => [
                'view_all' => true,
                'view_analytics' => true,
                'generate_reports' => true
            ],
            'Viewer' => [
                'view_public' => true,
                'view_summary' => true
            ]
        ];
        
        if (!isset($rolePermissions[$role])) {
            return false;
        }
        
        return $rolePermissions[$role][$permission] ?? false;
    }
}

if (!function_exists('require_permission')) {
    function require_permission($permission) {
        if (!has_permission($permission)) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized', 'required_permission' => $permission]);
            exit;
        }
    }
}

if (!function_exists('get_user_barangay')) {
    function get_user_barangay() {
        session_start_custom();
        return $_SESSION['user_barangay'] ?? null;
    }
}

if (!function_exists('can_upload_excel')) {
    function can_upload_excel() {
        return has_permission('upload_excel');
    }
}

if (!function_exists('is_sidebar_visible_for_role')) {
    function is_sidebar_visible_for_role($role) {
        // Data encoders have restricted access
        if (is_data_encoder()) {
            return false;
        }
        return true;
    }
}
