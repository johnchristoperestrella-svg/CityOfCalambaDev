<?php
// Test database connection
$db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');

if ($db->connect_error) {
    echo "❌ Database Connection Failed: " . $db->connect_error;
    exit;
}

echo "✅ Database Connected Successfully\n\n";

// Check if tables exist
$result = $db->query("SHOW TABLES");
echo "📊 Tables in database:\n";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        echo "  - " . $row[0] . "\n";
    }
} else {
    echo "  No tables found. Database may need migration.\n";
}

// Check configuration
echo "\n⚙️  Configuration:\n";
echo "  Host: 127.0.0.1\n";
echo "  Database: calamba_popdev\n";
echo "  User: root\n";

$db->close();
echo "\n✅ All systems operational!";
?>
