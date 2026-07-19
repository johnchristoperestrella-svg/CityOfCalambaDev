<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "Clearing old reports...\n";
$db->query('DELETE FROM reports');
echo "✅ Reports cleared\n\n";

echo "Inserting updated report data...\n";

$reports = [
    ['title' => 'Monthly Progress Report', 'description' => 'Monthly progress update on PopDev Resource Network initiatives', 'type' => 'monthly', 'generated_date' => '2026-04-20 10:30:00', 'published_date' => '2026-04-20 11:00:00', 'published_by' => 14, 'views' => 45, 'status' => 'published'],
    ['title' => 'Annual Development Goals', 'description' => 'Comprehensive review of annual development goals and progress', 'type' => 'annual', 'generated_date' => '2026-04-15 09:15:00', 'published_date' => '2026-04-15 10:00:00', 'published_by' => 14, 'views' => 78, 'status' => 'published'],
    ['title' => 'Risk Assessment Report', 'description' => 'Risk assessment and mitigation strategies for vulnerable populations', 'type' => 'risk_assessment', 'generated_date' => '2026-04-10 14:20:00', 'published_date' => '2026-04-10 15:00:00', 'published_by' => 14, 'views' => 32, 'status' => 'published'],
    ['title' => 'Health Initiative Impact', 'description' => 'Impact assessment of health initiatives in the community', 'type' => 'health', 'generated_date' => '2026-04-05 11:45:00', 'published_date' => '2026-04-05 12:30:00', 'published_by' => 14, 'views' => 56, 'status' => 'published'],
    ['title' => 'Education Program Evaluation', 'description' => 'Evaluation of education program effectiveness and outcomes', 'type' => 'education', 'generated_date' => '2026-04-01 08:30:00', 'published_date' => '2026-04-01 09:00:00', 'published_by' => 14, 'views' => 28, 'status' => 'published']
];

$inserted = 0;
foreach($reports as $report) {
    $sql = "INSERT INTO reports (title, description, type, generated_date, published_date, published_by, views, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssssssis', $report['title'], $report['description'], $report['type'], $report['generated_date'], $report['published_date'], $report['published_by'], $report['views'], $report['status']);
    if($stmt->execute()) {
        $inserted++;
        echo "✅ Inserted: {$report['title']}\n";
    } else {
        echo "❌ Failed to insert: {$report['title']}\n";
    }
    $stmt->close();
}

echo "\n✅ Complete! Inserted $inserted reports.\n";
?>
