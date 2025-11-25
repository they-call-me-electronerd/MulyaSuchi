<?php
define('MULYASUCHI_APP', true);
require_once '../config/constants.php';
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Item.php';

try {
    $itemObj = new Item();
    
    echo "Test 1: Search all items (no filters)\n";
    echo str_repeat("-", 50) . "\n";
    $items = $itemObj->searchProductsAdvanced(['limit' => 5]);
    echo "Found: " . count($items) . " items\n";
    if (count($items) > 0) {
        echo "First item: ID={$items[0]['item_id']}, Name={$items[0]['item_name']}, Price=NPR {$items[0]['current_price']}\n";
    }
    
    echo "\n\nTest 2: Search with text 'rice'\n";
    echo str_repeat("-", 50) . "\n";
    $items = $itemObj->searchProductsAdvanced(['search' => 'rice', 'limit' => 5]);
    echo "Found: " . count($items) . " items\n";
    foreach($items as $item) {
        echo "  - {$item['item_name']} (NPR {$item['current_price']})\n";
    }
    
    echo "\n\nTest 3: Filter by category 1 (Vegetables)\n";
    echo str_repeat("-", 50) . "\n";
    $items = $itemObj->searchProductsAdvanced(['category_id' => 1, 'limit' => 5]);
    echo "Found: " . count($items) . " items\n";
    foreach($items as $item) {
        echo "  - {$item['item_name']} (Category: {$item['category_name']})\n";
    }
    
    echo "\n\nTest 4: Count total active items\n";
    echo str_repeat("-", 50) . "\n";
    $count = $itemObj->countProductsAdvanced([]);
    echo "Total active items: {$count}\n";
    
    echo "\n\nTest 5: Search 'milk' with count\n";
    echo str_repeat("-", 50) . "\n";
    $items = $itemObj->searchProductsAdvanced(['search' => 'milk']);
    $count = $itemObj->countProductsAdvanced(['search' => 'milk']);
    echo "Found {$count} items total\n";
    echo "Returned " . count($items) . " items (no limit)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
