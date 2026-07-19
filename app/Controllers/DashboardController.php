<?php
namespace App\Controllers;

use App\Models\Barangay;
use App\Models\Individual;
use App\Models\Household;
use App\Models\HealthMetrics;
use App\Models\User;
use App\Models\AuditLog;

class DashboardController {
    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
    }

    public function index() {
        // Initialize models
        $barangayModel = new Barangay();
        $individualModel = new Individual();
        $householdModel = new Household();
        $healthModel = new HealthMetrics();
        $userModel = new User();
        $auditLogModel = new AuditLog();

        // OPTIMIZED: Load only top records, use COUNT() for totals
        $barangays = $barangayModel->getAll();
        $individuals = $individualModel->getAll(null, 1, 10);  // Load only top 10 instead of ALL
        $households = $householdModel->getAll(null, 1, 10);   // Load only top 10 instead of ALL
        $healthMetrics = $healthModel->getAllBarangayMetrics();
        $users = $userModel->getAll(null, 1, 10);  // Load only top 10 instead of ALL
        $auditLogs = $auditLogModel->getAll(50); // Get 50 recent logs
        
        // Use COUNT() queries instead of count(array) - bypasses loading entire dataset
        $totalBarangays = $barangayModel->getTotalCount();
        $totalHouseholds = $householdModel->getTotalCount();
        $totalIndividuals = $individualModel->getTotalCount();
        $totalUsers = $userModel->getTotalCount();
        
        // Calculate population from individuals
        $totalPopulation = $totalIndividuals;
        
        // Get socioeconomic data
        $socioeconomicData = $householdModel->getSocioeconomicData();
        
        $router = new \Router();
        return $router->render('dashboard.index', [
            'user' => auth_user(),
            'barangays' => $barangays,
            'totalBarangays' => $totalBarangays,
            'individuals' => $individuals,
            'totalIndividuals' => $totalIndividuals,
            'households' => $households,
            'totalHouseholds' => $totalHouseholds,
            'healthMetrics' => $healthMetrics,
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalPopulation' => $totalPopulation,
            'socioeconomicData' => $socioeconomicData,
            'auditLogs' => $auditLogs
        ]);
    }
}
