<?php
/**
 * Database Configuration and Connection
 * 
 * This file establishes a secure database connection using PDO
 * with prepared statements to prevent SQL injection.
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'mulyasuchi_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// PDO options for security and error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
];

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error securely (don't expose database details to users)
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}

?>
