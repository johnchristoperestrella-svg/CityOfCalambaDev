<?php
/**
 * Seed households for testing ML training
 * Run: php seed_households.php
 */

define('BASE_PATH', realpath(__DIR__));
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';

use App\Models\Household;

$householdModel = new Household();

// Sample household data for Barangay 1
$households = [
    [
        'barangay_id' => 1,
        'household_head' => 'Juan Dela Cruz',
        'address' => '123 Main St, Barangay 1',
        'member_count' => 5,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Maria Santos',
        'address' => '456 Secondary Ave, Barangay 1',
        'member_count' => 4,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Pedro Reyes',
        'address' => '789 Tertiary Rd, Barangay 1',
        'member_count' => 6,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Ana Lopez',
        'address' => '321 Oak Lane, Barangay 1',
        'member_count' => 3,
        'socioeconomic_status' => 'Middle'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Carlos Diaz',
        'address' => '654 Pine Street, Barangay 1',
        'member_count' => 7,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Rosa Garcia',
        'address' => '987 Maple Road, Barangay 1',
        'member_count' => 5,
        'socioeconomic_status' => 'Middle'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Antonio Flores',
        'address' => '147 Birch Ave, Barangay 1',
        'member_count' => 4,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Lucia Rivera',
        'address' => '258 Cedar Lane, Barangay 1',
        'member_count' => 6,
        'socioeconomic_status' => 'Low'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Miguel Torres',
        'address' => '369 Elm Street, Barangay 1',
        'member_count' => 5,
        'socioeconomic_status' => 'Middle'
    ],
    [
        'barangay_id' => 1,
        'household_head' => 'Isabel Mendez',
        'address' => '741 Ash Road, Barangay 1',
        'member_count' => 3,
        'socioeconomic_status' => 'Low'
    ]
];

echo "Seeding " . count($households) . " households for Barangay 1...\n";

$count = 0;
foreach ($households as $household) {
    try {
        $householdModel->create($household);
        $count++;
        echo "✓ Created: {$household['household_head']}\n";
    } catch (\Throwable $e) {
        echo "✗ Failed to create {$household['household_head']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Seeding complete! Created $count households.\n";
echo "You can now test ML training with barangay_id=1\n";
?>
