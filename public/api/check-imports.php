<?php
// Quick check script
require_once __DIR__ . '/../config/autoload.php';

$db = new Database();
$result = $db->query('SELECT * FROM data_imports');
$imports = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'count' => count($imports),
    'data' => $imports
], JSON_PRETTY_PRINT);
?>
