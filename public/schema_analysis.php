<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'calamba_popdev');

echo "═══════════════════════════════════════════════════════" . PHP_EOL;
echo "DATABASE SCHEMA - CALAMBA POPDEV" . PHP_EOL;
echo "═══════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

$result = $conn->query('SHOW TABLES');
while ($row = $result->fetch_row()) {
    $table = $row[0];
    $countResult = $conn->query('SELECT COUNT(*) as cnt FROM ' . $table);
    $count = $countResult->fetch_assoc()['cnt'];
    echo "📦 " . str_pad($table, 30) . " (" . $count . " records)" . PHP_EOL;
}

echo PHP_EOL . "KEY RELATIONSHIPS:" . PHP_EOL;
$fkResult = $conn->query("
    SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'calamba_popdev' AND REFERENCED_TABLE_NAME IS NOT NULL
");
while ($row = $fkResult->fetch_assoc()) {
    echo "  " . $row['TABLE_NAME'] . "." . $row['COLUMN_NAME'] . " → " . $row['REFERENCED_TABLE_NAME'] . "." . $row['REFERENCED_COLUMN_NAME'] . PHP_EOL;
}
