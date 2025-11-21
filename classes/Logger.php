<?php
/**
 * Logger Class
 * 
 * Handles system-wide logging for audit trails
 */

class Logger {
    
    /**
     * Log system activity
     */
    public static function log($actionType, $entityType = null, $entityId = null, $description = '') {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            $userId = Auth::getUserId();
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $stmt = $pdo->prepare("
                INSERT INTO system_logs (user_id, action_type, entity_type, entity_id, description, ip_address, user_agent)
                VALUES (:user_id, :action_type, :entity_type, :entity_id, :description, :ip_address, :user_agent)
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'action_type' => $actionType,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $description,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);
            
        } catch (PDOException $e) {
            // Fail silently but log to error log
            error_log("Logger error: " . $e->getMessage());
        }
    }
    
    /**
     * Get recent logs
     */
    public static function getRecentLogs($limit = 100, $userId = null) {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            $sql = "
                SELECT l.*, u.username, u.full_name
                FROM system_logs l
                LEFT JOIN users u ON l.user_id = u.user_id
            ";
            
            if ($userId) {
                $sql .= " WHERE l.user_id = :user_id";
            }
            
            $sql .= " ORDER BY l.created_at DESC LIMIT :limit";
            
            $stmt = $pdo->prepare($sql);
            
            if ($userId) {
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get logs error: " . $e->getMessage());
            return [];
        }
    }
}

?>
