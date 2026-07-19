<?php
$db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');
echo "=== DATA_IMPORTS TABLE STRUCTURE ===\n";
$result = $db->query('DESCRIBE data_imports');
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
}

echo "\n=== DATA_IMPORTS SAMPLE DATA ===\n";
$result = $db->query('SELECT * FROM data_imports LIMIT 3');
if($result->num_rows > 0) {
    $first_row = $result->fetch_assoc();
    foreach($first_row as $key => $value) {
        echo $key . ': ' . $value . PHP_EOL;
    }
}
?>
