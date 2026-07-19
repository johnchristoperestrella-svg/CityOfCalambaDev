<?php
/**
 * Migration: Create reports table for Decision Support
 * Date: 2026-06-06
 */

require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "Creating reports table...\n";

$sql = "CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT,
    type VARCHAR(50) NOT NULL COMMENT 'monthly, annual, risk_assessment, health, education, other',
    generated_date DATETIME NOT NULL,
    published_date DATETIME,
    published_by INT,
    views INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'draft' COMMENT 'draft, published, archived',
    content_path VARCHAR(255),
    import_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (published_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (import_id) REFERENCES data_imports(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_generated_date (generated_date),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    if($db->query($sql)) {
        echo "✅ Reports table created successfully\n";
    } else {
        echo "⚠️  Reports table may already exist\n";
    }
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nInserting initial report data...\n";

$reports = [
    [
        'title' => 'Monthly Progress Report',
        'description' => 'Monthly progress update on PopDev Resource Network initiatives',
        'type' => 'monthly',
        'generated_date' => '2026-04-20 10:30:00',
        'published_date' => '2026-04-20 11:00:00',
        'published_by' => 14,
        'views' => 45,
        'status' => 'published'
    ],
    [
        'title' => 'Annual Development Goals',
        'description' => 'Comprehensive review of annual development goals and progress',
        'type' => 'annual',
        'generated_date' => '2026-04-15 09:15:00',
        'published_date' => '2026-04-15 10:00:00',
        'published_by' => 14,
        'views' => 78,
        'status' => 'published'
    ],
    [
        'title' => 'Risk Assessment Report',
        'description' => 'Risk assessment and mitigation strategies for vulnerable populations',
        'type' => 'risk_assessment',
        'generated_date' => '2026-04-10 14:20:00',
        'published_date' => '2026-04-10 15:00:00',
        'published_by' => 14,
        'views' => 32,
        'status' => 'published'
    ],
    [
        'title' => 'Health Initiative Impact',
        'description' => 'Impact assessment of health initiatives in the community',
        'type' => 'health',
        'generated_date' => '2026-04-05 11:45:00',
        'published_date' => '2026-04-05 12:30:00',
        'published_by' => 14,
        'views' => 56,
        'status' => 'published'
    ],
    [
        'title' => 'Education Program Evaluation',
        'description' => 'Evaluation of education program effectiveness and outcomes',
        'type' => 'education',
        'generated_date' => '2026-04-01 08:30:00',
        'published_date' => '2026-04-01 09:00:00',
        'published_by' => 14,
        'views' => 28,
        'status' => 'published'
    ]
];

$inserted = 0;
foreach($reports as $report) {
    $sql = "INSERT IGNORE INTO reports (title, description, type, generated_date, published_date, published_by, views, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        'ssssssis',
        $report['title'],
        $report['description'],
        $report['type'],
        $report['generated_date'],
        $report['published_date'],
        $report['published_by'],
        $report['views'],
        $report['status']
    );
    
    if($stmt->execute()) {
        $inserted++;
        echo "✅ Inserted: {$report['title']}\n";
    } else {
        echo "⚠️  Could not insert: {$report['title']} (may already exist)\n";
    }
    $stmt->close();
}

echo "\n✅ Migration complete! Inserted $inserted reports.\n";
?>
