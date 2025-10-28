<?php

class User {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function find($id) {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function findByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function getAll($filters = []) {
        $where = "1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $where .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (isset($filters['is_active'])) {
            $where .= " AND is_active = ?";
            $params[] = $filters['is_active'];
        }

        $sql = "SELECT * FROM users WHERE {$where} ORDER BY created_at DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function count($filters = []) {
        $where = "1=1";
        $params = [];

        if (!empty($filters['role'])) {
            $where .= " AND role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (isset($filters['is_active'])) {
            $where .= " AND is_active = ?";
            $params[] = $filters['is_active'];
        }

        return $this->db->count('users', $where, $params);
    }

    public function create($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->db->insert('users', $data);
    }

    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }

    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }

    public function activate($id) {
        return $this->update($id, ['is_active' => 1]);
    }

    public function deactivate($id) {
        return $this->update($id, ['is_active' => 0]);
    }

    public function getTeachers() {
        return $this->db->fetchAll(
            "SELECT * FROM users WHERE role = 'teacher' AND is_active = 1 ORDER BY name"
        );
    }

    public function getStudents() {
        return $this->db->fetchAll(
            "SELECT * FROM users WHERE role = 'student' AND is_active = 1 ORDER BY name"
        );
    }

    public function getEnrolledCourses($userId) {
        $sql = "SELECT c.*, u.name as teacher_name, e.progress_percent, e.enrolled_at
                FROM courses c
                JOIN enrollments e ON c.id = e.course_id
                JOIN users u ON c.teacher_id = u.id
                WHERE e.user_id = ? AND c.is_published = 1
                ORDER BY e.enrolled_at DESC";
        
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getCoursesByTeacher($teacherId) {
        $sql = "SELECT * FROM courses WHERE teacher_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$teacherId]);
    }

    public function getStats($userId = null) {
        if ($userId) {
            // Individual user stats
            $stats = [];
            
            // For students
            $stats['enrolled_courses'] = $this->db->count('enrollments', 'user_id = ?', [$userId]);
            $stats['completed_lessons'] = $this->db->count('lesson_progress', 'user_id = ? AND is_completed = 1', [$userId]);
            $stats['quiz_attempts'] = $this->db->count('quiz_attempts', 'user_id = ?', [$userId]);
            $stats['assignments_submitted'] = $this->db->count('submissions', 'student_id = ?', [$userId]);
            
            // For teachers
            $stats['created_courses'] = $this->db->count('courses', 'teacher_id = ?', [$userId]);
            $stats['total_students'] = $this->db->fetch(
                "SELECT COUNT(DISTINCT e.user_id) as count 
                 FROM enrollments e 
                 JOIN courses c ON e.course_id = c.id 
                 WHERE c.teacher_id = ?", 
                [$userId]
            )['count'] ?? 0;
            
            return $stats;
        } else {
            // System-wide stats
            return [
                'total_users' => $this->db->count('users'),
                'total_students' => $this->db->count('users', 'role = ?', ['student']),
                'total_teachers' => $this->db->count('users', 'role = ?', ['teacher']),
                'active_users' => $this->db->count('users', 'is_active = 1'),
                'new_users_today' => $this->db->count('users', 'DATE(created_at) = CURDATE()'),
                'new_users_week' => $this->db->count('users', 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ];
        }
    }

    public function getRecentActivity($userId, $limit = 10) {
        // Get recent activities for a user
        $activities = [];
        
        // Recent enrollments
        $enrollments = $this->db->fetchAll(
            "SELECT 'enrollment' as type, c.title, e.enrolled_at as created_at
             FROM enrollments e
             JOIN courses c ON e.course_id = c.id
             WHERE e.user_id = ?
             ORDER BY e.enrolled_at DESC
             LIMIT ?",
            [$userId, $limit]
        );
        
        // Recent lesson completions
        $lessons = $this->db->fetchAll(
            "SELECT 'lesson_completed' as type, l.title, lp.completed_at as created_at
             FROM lesson_progress lp
             JOIN lessons l ON lp.lesson_id = l.id
             WHERE lp.user_id = ? AND lp.is_completed = 1
             ORDER BY lp.completed_at DESC
             LIMIT ?",
            [$userId, $limit]
        );
        
        // Recent quiz attempts
        $quizzes = $this->db->fetchAll(
            "SELECT 'quiz_attempt' as type, q.title, qa.finished_at as created_at, qa.score
             FROM quiz_attempts qa
             JOIN quizzes q ON qa.quiz_id = q.id
             WHERE qa.user_id = ? AND qa.finished_at IS NOT NULL
             ORDER BY qa.finished_at DESC
             LIMIT ?",
            [$userId, $limit]
        );
        
        // Merge and sort activities
        $activities = array_merge($enrollments, $lessons, $quizzes);
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($activities, 0, $limit);
    }
}