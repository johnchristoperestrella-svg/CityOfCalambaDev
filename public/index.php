<?php
/**
 * Main Application Entry Point
 * Calamba PopDev Resource Network
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't display errors in output (they break JSON responses)
ini_set('log_errors', 1);  // Log errors to error log instead

// Define base path (parent directory) - use realpath for absolute path resolution
define('BASE_PATH', realpath(__DIR__ . '/..')); 

// Autoload configuration and classes
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Router.php';

// Start session

session_start_custom();

// Initialize router
$router = new Router();

// ==================== Routes ====================

// Authentication Routes
$router->get('/', 'AuthController@login');
$router->get('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/api/login', 'AuthController@handleLogin');
$router->post('/api/register', 'AuthController@register');
$router->get('/api/check-email', 'AuthController@checkEmail');
$router->get('/api/logout', 'AuthController@logout');
$router->get('/api/user-profile', 'AuthController@getProfile');

// Dashboard Routes
$router->get('/dashboard', 'DashboardController@index');

// Account Routes
$router->get('/account', 'AccountController@index');
$router->post('/api/account/profile', 'AccountController@updateProfile');
$router->post('/api/account/change-password', 'AccountController@changePassword');
$router->post('/account/upload-photo', 'AccountController@uploadProfilePhoto');
$router->post('/account/remove-photo', 'AccountController@removeProfilePhoto');

// Data Management Routes
$router->get('/data-management', 'DataManagementController@index');
$router->get('/api/barangays', 'DataManagementController@getBarangays');
$router->get('/api/barangay-members/{barangayId}', 'DataManagementController@getBarangayMembers');
$router->get('/api/households', 'DataManagementController@getHouseholds');
$router->get('/api/individuals', 'DataManagementController@getIndividuals');
$router->get('/api/data-quality', 'DataManagementController@getDataQuality');
$router->post('/api/barangay/create', 'DataManagementController@createBarangay');
$router->put('/api/barangay/update/{id}', 'DataManagementController@updateBarangay');
$router->delete('/api/barangay/delete/{id}', 'DataManagementController@deleteBarangay');

// Data Import Routes
$router->get('/data-import', 'DataImportController@index');
$router->get('/data-import/encoder', 'DataImportController@encoderDashboard');
$router->get('/data-import/upload', 'DataImportController@uploadForm');
$router->post('/api/data-import/upload', 'DataImportController@handleUpload');
$router->get('/api/import/{id}', 'DataImportController@getImportDetails');
$router->get('/api/import-stats', 'DataImportController@getImportStats');
$router->post('/api/import/{id}/retry', 'DataImportController@retryImport');
$router->get('/api/import-history', 'DataImportController@getUploadHistory');
$router->get('/api/data-import/template', 'DataImportController@downloadTemplate');

// Analytics Routes
$router->get('/analytics', 'AnalyticsController@index');
$router->get('/analytics/import/{importId}', 'AnalyticsController@viewByImport');
$router->get('/analytics/barangay/{barangayId}', 'AnalyticsController@viewByBarangay');
$router->post('/api/analytics/compare', 'AnalyticsController@compareImports');
$router->get('/api/analytics/summary', 'AnalyticsController@getSummary');
$router->get('/api/analytics/metrics/{importId}', 'AnalyticsController@getMetrics');
$router->get('/analytics/export', 'AnalyticsController@exportAnalytics');

// Barangay Records Routes
$router->get('/barangay-records', 'BarangayRecordsController@index');
$router->get('/api/health-metrics/{barangayId}', 'BarangayRecordsController@getHealthMetrics');
$router->get('/api/malnutrition-data/{barangayId}', 'BarangayRecordsController@getMalnutritionData');
$router->get('/api/water-sanitation/{barangayId}', 'BarangayRecordsController@getWaterSanitation');

// Knowledge Management Routes
$router->get('/knowledge-management', 'KnowledgeManagementController@index');
$router->get('/api/documents', 'KnowledgeManagementController@getDocuments');
$router->post('/api/document/upload', 'KnowledgeManagementController@uploadDocument');
$router->get('/api/categories', 'KnowledgeManagementController@getCategories');
$router->get('/knowledge-management/download', 'KnowledgeManagementController@download');

// ML Analytics Routes
$router->get('/ml-analytics', 'MLAnalyticsController@index');
$router->get('/api/risk-predictions', 'MLAnalyticsController@getRiskPredictions');
$router->get('/api/population-forecast', 'MLAnalyticsController@getPopulationForecast');
$router->get('/api/clustering-results', 'MLAnalyticsController@getClusteringResults');
$router->get('/api/feature-importance', 'MLAnalyticsController@getFeatureImportance');
// ML model training endpoint (per-barangay)
$router->post('/api/ml/train', 'MLAnalyticsController@trainModel');

// Decision Support Routes
$router->get('/decision-support', 'DecisionSupportController@index');
$router->get('/api/dashboards', 'DecisionSupportController@getDashboards');
$router->get('/api/reports', 'DecisionSupportController@getReports');
$router->post('/api/policy-simulation', 'DecisionSupportController@runPolicySimulation');
$router->get('/api/analytics', 'DecisionSupportController@getAnalyticsData');
$router->get('/api/analytics/records-by-barangay', 'DecisionSupportController@getRecordsByBarangay');
$router->get('/api/analytics/import-trend', 'DecisionSupportController@getImportTrend');
$router->get('/api/analytics/data-quality', 'DecisionSupportController@getDataQuality');
$router->get('/api/analytics/population-by-age', 'DecisionSupportController@getPopulationByAge');

// Security & Governance Routes (Super Admin Only)
$router->get('/security-governance', 'SecurityGovernanceController@index');
$router->get('/api/users', 'SecurityGovernanceController@getUsers');
$router->post('/api/user/create', 'SecurityGovernanceController@createUser');
$router->put('/api/user/update/{id}', 'SecurityGovernanceController@updateUser');
$router->delete('/api/user/delete/{id}', 'SecurityGovernanceController@deleteUser');
$router->get('/api/imports', 'SecurityGovernanceController@getImports');
$router->delete('/api/import/delete/{id}', 'SecurityGovernanceController@deleteImport');
$router->get('/api/audit-logs', 'SecurityGovernanceController@getAuditLogs');
$router->get('/api/security-status', 'SecurityGovernanceController@getSecurityStatus');
$router->get('/api/role-permissions', 'SecurityGovernanceController@getRolePermissions');
$router->put('/api/user/{id}/permissions', 'SecurityGovernanceController@updateUserPermissions');
$router->get('/api/security-metrics', 'SecurityGovernanceController@getSystemSecurityMetrics');

// Barangay Management Routes (Super Admin Only)
$router->get('/api/barangay', 'SecurityGovernanceController@getBarangays');
$router->post('/api/barangay', 'SecurityGovernanceController@createBarangay');
$router->put('/api/barangay/{id}', 'SecurityGovernanceController@updateBarangay');
$router->delete('/api/barangay/{id}', 'SecurityGovernanceController@deleteBarangay');

// Dispatch request
echo $router->dispatch();
