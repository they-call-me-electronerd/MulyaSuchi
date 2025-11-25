<?php
require_once '../config/database.php';
require_once '../classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Count active items
    $stmt = $db->query('SELECT COUNT(*) as total FROM items WHERE status = 1');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Active Items in Database: " . $result['total'] . PHP_EOL;
    
    // Get category breakdown
    echo PHP_EOL . "Items by Category:" . PHP_EOL;
    $stmt = $db->query('
        SELECT c.category_name, COUNT(i.id) as count 
        FROM categories c 
        LEFT JOIN items i ON c.category_id = i.category_id AND i.status = 1 
        GROUP BY c.category_id, c.category_name
    ');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['category_name']}: {$row['count']} items" . PHP_EOL;
    }
    
    // Sample items
    echo PHP_EOL . 'Sample Active Items:' . PHP_EOL;
    $stmt = $db->query('SELECT id, name, category_id, price FROM items WHERE status = 1 LIMIT 10');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ID: {$row['id']}, Name: {$row['name']}, Category: {$row['category_id']}, Price: NPR {$row['price']}" . PHP_EOL;
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
