-- ============================================
-- Calamba PopDev Resource Network Database
-- ============================================

-- Create Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `role` ENUM('City Administrator', 'POPDEV Manager', 'Barangay Data Encoder', 'Analyst', 'Viewer') DEFAULT 'Viewer',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Barangays Table
CREATE TABLE IF NOT EXISTS `barangays` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `population` INT NOT NULL,
    `area` DECIMAL(10, 2) NOT NULL,
    `chairman` VARCHAR(255),
    `contact` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Households Table
CREATE TABLE IF NOT EXISTS `households` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `barangay_id` INT NOT NULL,
    `household_head` VARCHAR(255) NOT NULL,
    `address` VARCHAR(500),
    `member_count` INT NOT NULL,
    `socioeconomic_status` ENUM('Low', 'Lower Middle', 'Middle', 'Upper Middle', 'High') DEFAULT 'Low',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_barangay (barangay_id),
    INDEX idx_status (socioeconomic_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Individuals Table
CREATE TABLE IF NOT EXISTS `individuals` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `barangay_id` INT NOT NULL,
    `household_id` INT NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `age` INT,
    `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
    `health_status` ENUM('Healthy', 'At-Risk', 'Chronically Ill') DEFAULT 'Healthy',
    `education_level` ENUM('No Formal Education', 'Primary', 'Secondary', 'Tertiary') DEFAULT 'Primary',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    FOREIGN KEY (household_id) REFERENCES households(id),
    INDEX idx_barangay (barangay_id),
    INDEX idx_household (household_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Health Metrics Table
CREATE TABLE IF NOT EXISTS `health_metrics` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `barangay_id` INT NOT NULL,
    `immunization_coverage` DECIMAL(5, 2) DEFAULT 0,
    `maternal_mortality_rate` DECIMAL(8, 2) DEFAULT 0,
    `infant_mortality_rate` DECIMAL(8, 2) DEFAULT 0,
    `under5_mortality_rate` DECIMAL(8, 2) DEFAULT 0,
    `wasting` DECIMAL(5, 2) DEFAULT 0,
    `stunting` DECIMAL(5, 2) DEFAULT 0,
    `underweight` DECIMAL(5, 2) DEFAULT 0,
    `water_access_percent` DECIMAL(5, 2) DEFAULT 0,
    `sanitation_access_percent` DECIMAL(5, 2) DEFAULT 0,
    `recorded_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_barangay (barangay_id),
    INDEX idx_date (recorded_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Documents Table
CREATE TABLE IF NOT EXISTS `documents` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(500) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_type` VARCHAR(50),
    `uploaded_by` INT NOT NULL,
    `views` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    INDEX idx_category (category),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT,
    `action` VARCHAR(100) NOT NULL,
    `details` VARCHAR(500),
    `ip_address` VARCHAR(45),
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Risk Predictions Table (for ML results)
CREATE TABLE IF NOT EXISTS `risk_predictions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `household_id` INT NOT NULL,
    `risk_score` DECIMAL(5, 4),
    `risk_category` ENUM('Low Risk', 'Medium Risk', 'High Risk') DEFAULT 'Low Risk',
    `prediction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (household_id) REFERENCES households(id),
    INDEX idx_household (household_id),
    INDEX idx_date (prediction_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create ML Model Results Table
CREATE TABLE IF NOT EXISTS `ml_model_results` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `model_type` VARCHAR(100),
    `barangay_id` INT,
    `accuracy` DECIMAL(5, 4),
    `f1_score` DECIMAL(5, 4),
    `model_data` LONGTEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_model (model_type),
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Data Imports Table
CREATE TABLE IF NOT EXISTS `data_imports` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `file_name` VARCHAR(500),
    `file_path` VARCHAR(500),
    `barangay_id` INT,
    `total_records` INT DEFAULT 0,
    `processed_records` INT DEFAULT 0,
    `error_count` INT DEFAULT 0,
    `status` ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    `import_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `completed_date` TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_user (user_id),
    INDEX idx_barangay (barangay_id),
    INDEX idx_status (status),
    INDEX idx_date (import_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create User Permissions Table
CREATE TABLE IF NOT EXISTS `user_permissions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `permission_key` VARCHAR(100) NOT NULL,
    `permission_value` VARCHAR(100),
    `barangay_id` INT,
    `assigned_by` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_permission (user_id, permission_key, barangay_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (assigned_by) REFERENCES users(id),
    FOREIGN KEY (barangay_id) REFERENCES barangays(id),
    INDEX idx_user (user_id),
    INDEX idx_permission (permission_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Role Permissions Mapping Table
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `role` VARCHAR(100) NOT NULL UNIQUE,
    `permissions` LONGTEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default role permissions
INSERT IGNORE INTO `role_permissions` (`role`, `permissions`) VALUES 
('City Administrator', '["view_all","edit_all","delete_all","manage_users","manage_permissions","view_audit_logs","manage_system"]'),
('POPDEV Manager', '["view_all","edit_data","delete_data","manage_documents","upload_excel","view_analytics"]'),
('Barangay Data Encoder', '["view_assigned","edit_assigned","upload_excel","view_reports"]'),
('Analyst', '["view_all","view_analytics","generate_reports"]'),
('Viewer', '["view_public","view_summary"]');

-- Insert Default Admin User
INSERT IGNORE INTO `users` (`email`, `password`, `name`, `role`, `status`) VALUES 
('admin@calamba.gov.ph', '$2y$10$g0j9XEx02TieyGK.AlD4se3EIGUlTmIKZeRScnnDrG6JbDjDASjOy', 'City Administrator', 'City Administrator', 'active');

-- Insert Sample Barangays
INSERT IGNORE INTO `barangays` (`name`, `population`, `area`, `chairman`, `contact`) VALUES 
('Barangay 1', 45000, 2.5, 'Juan Dela Cruz', '+63-9XX-XXX-XXXX'),
('Barangay 2', 38000, 2.1, 'Maria Santos', '+63-9XX-XXX-XXXX'),
('Barangay 3', 52000, 3.0, 'Pedro Reyes', '+63-9XX-XXX-XXXX'),
('Barangay 4', 35000, 1.8, 'Ana Lopez', '+63-9XX-XXX-XXXX'),
('Barangay 5', 41000, 2.3, 'Carlos Diaz', '+63-9XX-XXX-XXXX');

-- Insert Sample Health Metrics
INSERT IGNORE INTO `health_metrics` (`barangay_id`, `immunization_coverage`, `maternal_mortality_rate`, `infant_mortality_rate`, `under5_mortality_rate`, `wasting`, `stunting`, `underweight`, `water_access_percent`, `sanitation_access_percent`) VALUES 
(1, 92.5, 45.5, 18.2, 22.5, 5.2, 8.3, 6.1, 88.5, 82.3),
(2, 89.3, 52.1, 21.5, 25.3, 6.8, 10.2, 7.9, 85.2, 79.1),
(3, 95.1, 38.2, 15.5, 18.9, 3.5, 6.1, 4.8, 92.3, 88.5),
(4, 87.5, 58.3, 24.1, 28.2, 8.1, 12.5, 9.3, 81.2, 75.3),
(5, 91.2, 48.5, 19.8, 23.5, 5.8, 9.1, 6.9, 89.1, 84.2);
