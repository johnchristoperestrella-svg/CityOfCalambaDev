#!/usr/bin/env php
<?php
/**
 * Database Migration Setup Script
 * Applies all pending database migrations
 */

// Define base path
define('BASE_PATH', __DIR__);

// Load configuration first
require_once BASE_PATH . '/config/helpers.php';
require_once BASE_PATH . '/config/autoload.php';

// Connect to database
require_once BASE_PATH . '/config/database.php';

$db = new Database();

echo "🔄 Applying database migrations...\n\n";

// Migration 1: Initial tables (already applied in setup)
echo "✓ Migration 001_create_initial_tables.sql (already applied)\n";

// Migration 2: Analytics and import tracking
echo "Applying Migration 002_add_analytics_and_import_tracking.sql...\n";

$migrationFile = BASE_PATH . '/database/migrations/002_add_analytics_and_import_tracking.sql';

if (!file_exists($migrationFile)) {
    echo "❌ Migration file not found: $migrationFile\n";
    exit(1);
}

$sqlContent = file_get_contents($migrationFile);

// Split SQL statements by semicolon
$statements = array_filter(array_map('trim', explode(';', $sqlContent)));

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    if (empty($statement)) {
        continue;
    }

    try {
        $db->query($statement);
        $successCount++;
        echo ".";
    } catch (Exception $e) {
        echo "E";
        $errorCount++;
        error_log("Migration error: " . $e->getMessage() . "\nStatement: " . substr($statement, 0, 100));
    }
}

echo "\n\n";

if ($errorCount === 0) {
    echo "✅ All migrations applied successfully!\n";
    echo "   $successCount SQL statements executed\n";
    exit(0);
} else {
    echo "⚠️  Migration completed with errors\n";
    echo "   $successCount successful\n";
    echo "   $errorCount errors\n";
    exit(1);
}
