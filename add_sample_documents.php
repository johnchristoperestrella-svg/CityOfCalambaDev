<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Adding Sample Documents ===\n";

$documents = [
    [
        'title' => 'Community Health Guidelines',
        'category' => 'Health',
        'file_path' => 'documents/health_guidelines.pdf',
        'file_type' => 'application/pdf',
        'views' => 42
    ],
    [
        'title' => 'Population Data Analysis Report 2024',
        'category' => 'Reports',
        'file_path' => 'documents/population_analysis.pdf',
        'file_type' => 'application/pdf',
        'views' => 67
    ],
    [
        'title' => 'Environmental Sustainability Handbook',
        'category' => 'Environment',
        'file_path' => 'documents/sustainability.pdf',
        'file_type' => 'application/pdf',
        'views' => 28
    ],
    [
        'title' => 'Education Program Frameworks',
        'category' => 'Education',
        'file_path' => 'documents/education_framework.pdf',
        'file_type' => 'application/pdf',
        'views' => 55
    ],
    [
        'title' => 'Agricultural Development Strategy',
        'category' => 'Agriculture',
        'file_path' => 'documents/agriculture_strategy.pdf',
        'file_type' => 'application/pdf',
        'views' => 31
    ],
    [
        'title' => 'Social Services Manual',
        'category' => 'Social Services',
        'file_path' => 'documents/social_services.pdf',
        'file_type' => 'application/pdf',
        'views' => 44
    ],
    [
        'title' => 'Infrastructure Development Plans',
        'category' => 'Infrastructure',
        'file_path' => 'documents/infrastructure_plans.pdf',
        'file_type' => 'application/pdf',
        'views' => 38
    ],
    [
        'title' => 'Emergency Response Procedures',
        'category' => 'Emergency',
        'file_path' => 'documents/emergency_procedures.pdf',
        'file_type' => 'application/pdf',
        'views' => 52
    ]
];

$count = 0;
foreach ($documents as $doc) {
    $sql = "INSERT INTO documents (title, category, file_path, file_type, uploaded_by, views, created_at, updated_at) 
            VALUES ('{$db->escape($doc['title'])}', '{$db->escape($doc['category'])}', '{$db->escape($doc['file_path'])}', 
                    '{$db->escape($doc['file_type'])}', 2, {$doc['views']}, NOW(), NOW())";
    
    if ($db->query($sql)) {
        echo "✓ Added: {$doc['title']}\n";
        $count++;
    }
}

echo "\n=== Summary ===\n";
echo "Documents added: {$count}\n";

$result = $db->query("SELECT COUNT(*) as cnt FROM documents");
$row = $result->fetch_assoc();
echo "Total documents now: " . $row['cnt'] . "\n";

$categories = $db->query("SELECT COUNT(DISTINCT category) as cnt FROM documents");
$cat_row = $categories->fetch_assoc();
echo "Total categories: " . $cat_row['cnt'] . "\n";
?>
