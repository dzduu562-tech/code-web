<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        return $this->findOneBy('email', $email);
    }

    /**
     * Create new user
     */
    public function create($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], HASH_ALGO);
        
        // Generate verification token if needed
        if (!isset($data['email_verified'])) {
            $data['email_verified'] = 0;
            $data['verification_token'] = generateToken();
        }
        
        return $this->insert($data);
    }

    /**
     * Verify password
     */
    public function verifyPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword);
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id";
        return $this->query($sql, ['id' => $userId]);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role) {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role AND status = 'active' ORDER BY full_name ASC";
        return $this->query($sql, ['role' => $role])->fetchAll();
    }

    /**
     * Get students
     */
    public function getStudents() {
        $sql = "SELECT u.*, sp.student_code, sp.total_xp, sp.level 
                FROM {$this->table} u 
                LEFT JOIN student_profiles sp ON u.id = sp.user_id 
                WHERE u.role = 'student' AND u.status = 'active'
                ORDER BY u.full_name ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get teachers
     */
    public function getTeachers() {
        $sql = "SELECT u.*, tp.teacher_code, tp.specialization 
                FROM {$this->table} u 
                LEFT JOIN teacher_profiles tp ON u.id = tp.user_id 
                WHERE u.role = 'teacher' AND u.status = 'active'
                ORDER BY u.full_name ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Search users
     */
    public function search($keyword, $role = null) {
        $sql = "SELECT * FROM {$this->table} WHERE (full_name LIKE :keyword OR email LIKE :keyword)";
        $params = ['keyword' => "%{$keyword}%"];
        
        if ($role) {
            $sql .= " AND role = :role";
            $params['role'] = $role;
        }
        
        $sql .= " ORDER BY full_name ASC";
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Get user with profile
     */
    public function getUserWithProfile($userId) {
        $user = $this->findById($userId);
        
        if (!$user) {
            return null;
        }
        
        // Get role-specific profile
        if ($user['role'] === 'student') {
            $sql = "SELECT * FROM student_profiles WHERE user_id = :user_id";
            $user['profile'] = $this->query($sql, ['user_id' => $userId])->fetch();
        } elseif ($user['role'] === 'teacher') {
            $sql = "SELECT * FROM teacher_profiles WHERE user_id = :user_id";
            $user['profile'] = $this->query($sql, ['user_id' => $userId])->fetch();
        }
        
        return $user;
    }

    /**
     * Update profile
     */
    public function updateProfile($userId, $data) {
        $allowedFields = ['full_name', 'phone', 'address', 'avatar'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (!empty($updateData)) {
            return $this->update($userId, $updateData);
        }
        
        return false;
    }

    /**
     * Change password
     */
    public function changePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, HASH_ALGO);
        return $this->update($userId, ['password' => $hashedPassword]);
    }

    /**
     * Generate password reset token
     */
    public function generateResetToken($email) {
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "UPDATE {$this->table} SET reset_token = :token, reset_expires = :expires WHERE email = :email";
        $this->query($sql, [
            'token' => $token,
            'expires' => $expires,
            'email' => $email
        ]);
        
        return $token;
    }

    /**
     * Verify reset token
     */
    public function verifyResetToken($token) {
        $sql = "SELECT * FROM {$this->table} WHERE reset_token = :token AND reset_expires > NOW()";
        return $this->query($sql, ['token' => $token])->fetch();
    }

    /**
     * Reset password with token
     */
    public function resetPassword($token, $newPassword) {
        $user = $this->verifyResetToken($token);
        
        if (!$user) {
            return false;
        }
        
        $hashedPassword = password_hash($newPassword, HASH_ALGO);
        $sql = "UPDATE {$this->table} SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id";
        
        return $this->query($sql, [
            'password' => $hashedPassword,
            'id' => $user['id']
        ]);
    }
}
