<?php
/**
 * User Class
 * 
 * Handles user management operations (admin use)
 */

class User {
    private $pdo;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    /**
     * Create new user (for self-registration)
     */
    public function createUser($username, $email, $password, $fullName, $role = ROLE_CONTRIBUTOR) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, full_name, role, status)
                VALUES (:username, :email, :password_hash, :full_name, :role, :status)
            ");
            
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            $result = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
                'full_name' => $fullName,
                'role' => $role,
                'status' => STATUS_ACTIVE
            ]);
            
            if ($result) {
                $userId = $this->pdo->lastInsertId();
                Logger::log(LOG_CREATE, 'user', $userId, "User $username registered");
                return $userId;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Create user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new user (admin version with created_by)
     */
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, full_name, role, status, phone, created_by)
                VALUES (:username, :email, :password_hash, :full_name, :role, :status, :phone, :created_by)
            ");
            
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
            
            $result = $stmt->execute([
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => $passwordHash,
                'full_name' => $data['full_name'],
                'role' => $data['role'] ?? ROLE_CONTRIBUTOR,
                'status' => $data['status'] ?? STATUS_ACTIVE,
                'phone' => $data['phone'] ?? null,
                'created_by' => Auth::getUserId()
            ]);
            
            if ($result) {
                $userId = $this->pdo->lastInsertId();
                Logger::log(LOG_CREATE, 'user', $userId, "User {$data['username']} created");
                return $userId;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Create user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users
     */
    public function getAllUsers($role = null) {
        try {
            $sql = "SELECT * FROM users";
            
            if ($role) {
                $sql .= " WHERE role = :role";
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            
            if ($role) {
                $stmt->bindValue(':role', $role, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Get user by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update user
     */
    public function update($userId, $data) {
        try {
            $sql = "UPDATE users SET ";
            $params = ['user_id' => $userId];
            $updates = [];
            
            if (isset($data['full_name'])) {
                $updates[] = "full_name = :full_name";
                $params['full_name'] = $data['full_name'];
            }
            
            if (isset($data['email'])) {
                $updates[] = "email = :email";
                $params['email'] = $data['email'];
            }
            
            if (isset($data['phone'])) {
                $updates[] = "phone = :phone";
                $params['phone'] = $data['phone'];
            }
            
            if (isset($data['status'])) {
                $updates[] = "status = :status";
                $params['status'] = $data['status'];
            }
            
            if (isset($data['role'])) {
                $updates[] = "role = :role";
                $params['role'] = $data['role'];
            }
            
            if (isset($data['password']) && !empty($data['password'])) {
                $updates[] = "password_hash = :password_hash";
                $params['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $sql .= implode(', ', $updates) . " WHERE user_id = :user_id";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                Logger::log(LOG_UPDATE, 'user', $userId, "User updated");
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status) {
        return $this->update($userId, ['status' => $status]);
    }
    
    /**
     * Delete user (alias for admin panel)
     */
    public function deleteUser($userId) {
        return $this->delete($userId);
    }
    
    /**
     * Delete user
     */
    public function delete($userId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
            $result = $stmt->execute(['user_id' => $userId]);
            
            if ($result) {
                Logger::log(LOG_DELETE, 'user', $userId, "User deleted");
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Count users by role
     */
    public function countUsers($role = null) {
        try {
            $sql = "SELECT COUNT(*) FROM users";
            
            if ($role) {
                $sql .= " WHERE role = :role";
            }
            
            $stmt = $this->pdo->prepare($sql);
            
            if ($role) {
                $stmt->bindValue(':role', $role, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            error_log("Count users error: " . $e->getMessage());
            return 0;
        }
    }
}

?>
