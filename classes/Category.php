<?php
/**
 * Category Class
 * 
 * Handles all category-related operations
 */

class Category {
    private $pdo;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    /**
     * Get all active categories
     */
    public function getActiveCategories() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM categories
                WHERE is_active = 1
                ORDER BY display_order ASC, category_name ASC
            ");
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get active categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get category by ID
     */
    public function getCategoryById($categoryId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE category_id = :category_id");
            $stmt->execute(['category_id' => $categoryId]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Get category by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = :slug AND is_active = 1");
            $stmt->execute(['slug' => $slug]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Get category by slug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get categories with item counts
     */
    public function getCategoriesWithItemCounts() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.*, COUNT(i.item_id) as item_count
                FROM categories c
                LEFT JOIN items i ON c.category_id = i.category_id AND i.status = :status
                WHERE c.is_active = 1
                GROUP BY c.category_id
                ORDER BY c.display_order ASC, c.category_name ASC
            ");
            
            $stmt->execute(['status' => ITEM_STATUS_ACTIVE]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get categories with item counts error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get items by category with limit
     */
    public function getItemsByCategory($categoryId, $limit = 5) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT i.*, c.category_name
                FROM items i
                JOIN categories c ON i.category_id = c.category_id
                WHERE i.category_id = :category_id AND i.status = :status
                ORDER BY i.updated_at DESC
                LIMIT :limit
            ");
            
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':status', ITEM_STATUS_ACTIVE, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get items by category error: " . $e->getMessage());
            return [];
        }
    }
}

?>
