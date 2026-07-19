<?php
require_once '../config/database.php';

$db = new Database();
$result = $db->query("SELECT * FROM data_imports LIMIT 5");
$imports = $result->fetch_all(MYSQLI_ASSOC);

echo "Imports in database:\n";
echo json_encode($imports, JSON_PRETTY_PRINT) . "\n";
?>
