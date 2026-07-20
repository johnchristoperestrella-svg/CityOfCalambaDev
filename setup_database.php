<?php
/**
 * Database Setup Script
 * Creates the database and tables if they don't exist
 */

// Suppress the error display since we're testing connections
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Define base path
define('BASE_PATH', __DIR__);

require_once __DIR__ . '/config/helpers.php';

// Try to connect without database first to create it
$host = env('DB_HOST', env('MYSQL_HOST', '127.0.0.1'));
$port = env('DB_PORT', env('MYSQL_PORT', 3306));
$username = env('DB_USERNAME', env('MYSQL_USERNAME', 'root'));
$password = env('DB_PASSWORD', env('MYSQL_PASSWORD', ''));
$dbName = env('DB_DATABASE', env('MYSQL_DATABASE', 'calamba_popdev'));

if (empty($host) || empty($username)) {
    die("Error: Database host and username must be configured.\n");
}

echo "Setting up database...\n";

echo "Using host: {$host}:{$port}\n";

// Connect to MySQL server (without specifying a database)
$serverConnection = @new mysqli($host, $username, $password, '', (int) $port);

if ($serverConnection->connect_error) {
    die("Error: Could not connect to MySQL server. Make sure MySQL/MariaDB is running.\n");
}

echo "Connected to MySQL server.\n";

// Create database
$createDbSQL = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($serverConnection->query($createDbSQL)) {
    echo "✓ Database '$dbName' created/verified.\n";
} else {
    die("Error creating database: " . $serverConnection->error . "\n");
}

$serverConnection->close();

// Now connect to the database
$connection = @new mysqli($host, $username, $password, $dbName, (int) $port);

if ($connection->connect_error) {
    die("Error: Could not connect to database '$dbName'. " . $connection->connect_error . "\n");
}

echo "Connected to database '$dbName'.\n";

// Read and execute migration file
$migrationFile = __DIR__ . '/database/migrations/001_create_initial_tables.sql';

if (!file_exists($migrationFile)) {
    die("Migration file not found: $migrationFile\n");
}

$sql = file_get_contents($migrationFile);

// Split SQL statements and execute them
$statements = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if ($connection->query($statement)) {
            $successCount++;
        } else {
            echo "Warning: Failed to execute statement. Error: " . $connection->error . "\n";
            echo "Statement: " . substr($statement, 0, 100) . "...\n";
            $errorCount++;
        }
    }
}

echo "\n✓ Database setup complete!\n";
echo "  - $successCount statements executed successfully\n";
if ($errorCount > 0) {
    echo "  - $errorCount statements failed (may be expected)\n";
}

$connection->close();

echo "\nDatabase setup finished. You can now run the application.\n";
?>
