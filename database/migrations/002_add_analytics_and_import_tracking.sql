-- ============================================
-- Add Analytics & Import Tracking Tables
-- ============================================

-- Update data_imports table to add analytics fields
ALTER TABLE `data_imports` ADD COLUMN IF NOT EXISTS `total_households` INT DEFAULT 0 AFTER `processed_records`;
ALTER TABLE `data_imports` ADD COLUMN IF NOT EXISTS `total_individuals` INT DEFAULT 0 AFTER `total_households`;
ALTER TABLE `data_imports` ADD COLUMN IF NOT EXISTS `average_household_size` DECIMAL(5, 2) DEFAULT 0 AFTER `total_individuals`;
ALTER TABLE `data_imports` ADD COLUMN IF NOT EXISTS `socioeconomic_summary` LONGTEXT AFTER `average_household_size`;

-- Create Analytics Table (stores computed analytics for each import)
CREATE TABLE IF NOT EXISTS `import_analytics` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `import_id` INT NOT NULL UNIQUE,
    `barangay_id` INT NOT NULL,
    `total_records` INT DEFAULT 0,
    `total_households` INT DEFAULT 0,
    `total_individuals` INT DEFAULT 0,
    `average_household_size` DECIMAL(5, 2) DEFAULT 0,
    `average_age` DECIMAL(5, 2) DEFAULT 0,
    `gender_distribution` JSON,
    `education_distribution` JSON,
    `health_status_distribution` JSON,
    `socioeconomic_distribution` JSON,
    `low_income_households` INT DEFAULT 0,
    `low_income_percentage` DECIMAL(5, 2) DEFAULT 0,
    `health_at_risk_count` INT DEFAULT 0,
    `health_at_risk_percentage` DECIMAL(5, 2) DEFAULT 0,
    `key_findings` LONGTEXT,
    `recommendations` LONGTEXT,
    `generated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (import_id) REFERENCES data_imports(id) ON DELETE CASCADE,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_import (import_id),
    INDEX idx_barangay (barangay_id),
    INDEX idx_generated (generated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add import_id tracking to households
ALTER TABLE `households` ADD COLUMN IF NOT EXISTS `import_id` INT DEFAULT NULL AFTER `barangay_id`;
ALTER TABLE `households` ADD FOREIGN KEY (`import_id`) REFERENCES data_imports(id) ON DELETE SET NULL;
ALTER TABLE `households` ADD INDEX idx_import (import_id);

-- Add import_id tracking to individuals
ALTER TABLE `individuals` ADD COLUMN IF NOT EXISTS `import_id` INT DEFAULT NULL AFTER `barangay_id`;
ALTER TABLE `individuals` ADD FOREIGN KEY (`import_id`) REFERENCES data_imports(id) ON DELETE SET NULL;
ALTER TABLE `individuals` ADD INDEX idx_import (import_id);

-- Create Analytics Comparison Table (for comparing multiple imports)
CREATE TABLE IF NOT EXISTS `analytics_comparison` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `import_id_1` INT NOT NULL,
    `import_id_2` INT NOT NULL,
    `barangay_id` INT NOT NULL,
    `households_difference` INT,
    `individuals_difference` INT,
    `avg_size_difference` DECIMAL(5, 2),
    `socioeconomic_change` JSON,
    `health_status_change` JSON,
    `compared_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (import_id_1) REFERENCES data_imports(id) ON DELETE CASCADE,
    FOREIGN KEY (import_id_2) REFERENCES data_imports(id) ON DELETE CASCADE,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_comparison (import_id_1, import_id_2),
    INDEX idx_barangay (barangay_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
