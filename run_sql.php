<?php
require_once 'config/database.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check if query parameter is provided
    if(isset($_GET['query'])) {
        $sql = $_GET['query'];
        $stmt = $conn->query($sql);
        
        // If it's a SELECT or DESCRIBE query, show results
        if(stripos($sql, 'SELECT') === 0 || stripos($sql, 'DESCRIBE') === 0 || stripos($sql, 'SHOW') === 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>" . print_r($results, true) . "</pre>";
        } else {
            echo "Query executed successfully.\n";
        }
    } else {
        echo "No query provided. Use ?query=YOUR_SQL_HERE\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
