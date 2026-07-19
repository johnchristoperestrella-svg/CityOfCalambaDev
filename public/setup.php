<?php
// Database setup and initialization script
$servername = "127.0.0.1";
$username = "root";
$password = "";

// Create connection without selecting database first
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "✓ Connected to MySQL<br>";

// Create database
$database = "calamba_popdev";
$sql = "CREATE DATABASE IF NOT EXISTS $database DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if (mysqli_query($conn, $sql)) {
    echo "✓ Database created or already exists<br>";
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Select database
mysqli_select_db($conn, $database) or die("Can't use database: " . mysqli_error($conn));
echo "✓ Database selected<br>";

// Drop existing users table to recreate with status field
mysqli_query($conn, "DROP TABLE IF EXISTS users");

// Create users table
$users_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    password VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'Viewer',
    status VARCHAR(20) DEFAULT 'active',
    barangay_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $users_sql)) {
    echo "✓ Users table ready<br>";
} else {
    echo "Users table: " . mysqli_error($conn) . "<br>";
}

// Create barangays table
$barangays_sql = "CREATE TABLE IF NOT EXISTS barangays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    population INT DEFAULT 0,
    total_households INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $barangays_sql)) {
    echo "✓ Barangays table ready<br>";
} else {
    echo "Barangays table: " . mysqli_error($conn) . "<br>";
}

// Create other essential tables
$audit_logs_sql = "CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    details TEXT,
    email VARCHAR(255),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $audit_logs_sql)) {
    echo "✓ Audit logs table ready<br>";
} else {
    echo "Audit logs: " . mysqli_error($conn) . "<br>";
}

$households_sql = "CREATE TABLE IF NOT EXISTS households (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barangay_id INT,
    household_head VARCHAR(255),
    address VARCHAR(255),
    member_count INT,
    income DECIMAL(12,2),
    socioeconomic_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $households_sql)) {
    echo "✓ Households table ready<br>";
} else {
    echo "Households table: " . mysqli_error($conn) . "<br>";
}

$individuals_sql = "CREATE TABLE IF NOT EXISTS individuals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    household_id INT,
    name VARCHAR(255),
    age INT,
    gender VARCHAR(10),
    occupation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (mysqli_query($conn, $individuals_sql)) {
    echo "✓ Individuals table ready<br>";
} else {
    echo "Individuals table: " . mysqli_error($conn) . "<br>";
}

// Insert test barangays if not exist
$check_barangay = "SELECT COUNT(*) as count FROM barangays";
$result = mysqli_query($conn, $check_barangay);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $barangays = ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5'];
    foreach ($barangays as $brgy) {
        $sql = "INSERT INTO barangays (name, population) VALUES ('$brgy', 5000)";
        mysqli_query($conn, $sql);
    }
    echo "✓ Sample barangays inserted<br>";
}

// Insert test admin user if not exist
$check_admin = "SELECT * FROM users WHERE email = 'admin@calamba.gov.ph'";
$result = mysqli_query($conn, $check_admin);

if (mysqli_num_rows($result) == 0) {
    $password_hash = password_hash("password", PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (email, password_hash, name, role, barangay_id) 
            VALUES ('admin@calamba.gov.ph', '$password_hash', 'Admin User', 'City Administrator', 1)";
    
    if (mysqli_query($conn, $sql)) {
        echo "✓ Admin user created (email: admin@calamba.gov.ph, password: password)<br>";
    }
}

// Insert test encoder user
$check_encoder = "SELECT * FROM users WHERE email = 'encoder@calamba.gov.ph'";
$result = mysqli_query($conn, $check_encoder);

if (mysqli_num_rows($result) == 0) {
    $password_hash = password_hash("password", PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (email, password_hash, name, role, barangay_id) 
            VALUES ('encoder@calamba.gov.ph', '$password_hash', 'Data Encoder', 'Barangay Data Encoder', 1)";
    
    if (mysqli_query($conn, $sql)) {
        echo "✓ Encoder user created (email: encoder@calamba.gov.ph, password: password)<br>";
    }
}

echo "<br><strong>✅ Database initialization complete!</strong><br>";
echo "<br>Login credentials:<br>";
echo "Super Admin: admin@calamba.gov.ph / password<br>";
echo "Data Encoder: encoder@calamba.gov.ph / password<br>";
echo "<br><a href='/'>Back to login</a>";

mysqli_close($conn);
?>
