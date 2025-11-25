<?php
/**
 * Load 500 Products Database Seed
 * Execute this file to load all 500 products into the database
 */

echo "==============================================\n";
echo "MulyaSuchi: Loading 500 Products Database\n";
echo "==============================================\n\n";

require_once 'config/database.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Set attributes for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $files = [
        'sql/fresh_500_products.sql',
        'sql/fresh_500_products_part2.sql',
        'sql/fresh_500_products_part3.sql'
    ];
    
    foreach ($files as $index => $file) {
        $partNum = $index + 1;
        echo "[$partNum/3] Loading: $file\n";
        
        if (!file_exists($file)) {
            throw new Exception("File not found: $file");
        }
        
        $sql = file_get_contents($file);
        
        // Split by semicolons and execute each statement
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($stmt) {
                return !empty($stmt) && 
                       !preg_match('/^--/', $stmt) && 
                       !preg_match('/^\/\*/', $stmt);
            }
        );
        
        $count = 0;
        foreach ($statements as $statement) {
            if (trim($statement)) {
                try {
                    $pdo->exec($statement);
                    $count++;
                } catch (PDOException $e) {
                    // Skip use database and select statements
                    if (stripos($statement, 'USE ') !== 0 && 
                        stripos($statement, 'SELECT') !== 0 &&
                        stripos($statement, 'SOURCE') !== 0) {
                        echo "  Warning: " . substr($e->getMessage(), 0, 100) . "...\n";
                    }
                }
            }
        }
        
        echo "  ✓ Executed $count SQL statements\n\n";
    }
    
    // Verification
    echo "==============================================\n";
    echo "Verification\n";
    echo "==============================================\n\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM items");
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "✓ Total Products: $totalProducts\n\n";
    
    $stmt = $pdo->query("
        SELECT c.category_name, COUNT(i.item_id) as count
        FROM categories c
        LEFT JOIN items i ON c.category_id = i.category_id
        GROUP BY c.category_id, c.category_name
        ORDER BY c.category_id
    ");
    
    echo "Category Distribution:\n";
    echo str_repeat("-", 50) . "\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("  %-30s : %3d products\n", $row['category_name'], $row['count']);
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ SUCCESS! Database loaded with $totalProducts products!\n";
    echo str_repeat("=", 50) . "\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}
?>
