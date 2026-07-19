<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>System Status</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .ok { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        h2 { color: #333; }
    </style>
</head>
<body>
<h1>🏥 City of Calamba - Population Development System Status</h1>";

// Check Database
echo "<h2>Database Status</h2>";
$conn = @mysqli_connect("127.0.0.1", "root", "", "calamba_popdev");
if ($conn) {
    echo "<div class='status ok'>✅ Database Connected Successfully</div>";
    
    $result = mysqli_query($conn, "SHOW TABLES");
    $tables = [];
    while($row = mysqli_fetch_assoc($result)) {
        $tables[] = array_values($row)[0];
    }
    echo "<p><strong>Tables:</strong> " . count($tables) . " found</p>";
    echo "<ul>";
    foreach($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Check data
    echo "<h2>Data Summary</h2>";
    $counts = [
        'users' => 'Users',
        'households' => 'Households',
        'individuals' => 'Individuals',
        'barangays' => 'Barangays',
        'health_metrics' => 'Health Records',
        'documents' => 'Documents'
    ];
    
    foreach($counts as $table => $label) {
        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM $table");
        $row = mysqli_fetch_assoc($result);
        echo "<p><strong>$label:</strong> " . $row['count'] . "</p>";
    }
} else {
    echo "<div class='status error'>❌ Database Connection Failed</div>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}

echo "<h2>File Structure</h2>";
$files_ok = [
    'config/database.php' => file_exists('../config/database.php'),
    'config/helpers.php' => file_exists('../config/helpers.php'),
    'app/Controllers/AuthController.php' => file_exists('../app/Controllers/AuthController.php'),
    'app/Controllers/DataImportController.php' => file_exists('../app/Controllers/DataImportController.php'),
    'public/js/app.js' => file_exists('js/app.js'),
    'public/css/style.css' => file_exists('css/style.css'),
];

foreach($files_ok as $file => $exists) {
    $status = $exists ? '✅' : '❌';
    echo "<p>$status $file</p>";
}

echo "<h2>Next Steps</h2>";
echo "<ol>
    <li><a href='index.php'>Go to Login Page</a></li>
    <li>Login with: <code>admin@calamba.gov.ph / password</code> (Super Admin)</li>
    <li>Or login as Data Encoder: <code>encoder@calamba.gov.ph / password</code></li>
</ol>";

echo "</body></html>";
?>
