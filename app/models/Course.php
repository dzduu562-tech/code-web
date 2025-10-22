<?php
/**
 * Course Model
 */

class Course extends Model {
    protected $table = 'courses';

    /**
     * Get published courses
     */
    public function getPublishedCourses($limit = null) {
        $sql = "SELECT c.*, s.name as subject_name, u.full_name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1 AND c.status = 'published'
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get featured courses
     */
    public function getFeaturedCourses($limit = 6) {
        $sql = "SELECT c.*, s.name as subject_name, u.full_name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1 AND c.is_featured = 1 AND c.status = 'published'
                ORDER BY c.enrollment_count DESC, c.created_at DESC
                LIMIT {$limit}";
        
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get course by slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT c.*, s.name as subject_name, s.color as subject_color,
                u.full_name as teacher_name, u.avatar as teacher_avatar,
                tp.specialization as teacher_specialization
                FROM {$this->table} c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.teacher_id = u.id
                LEFT JOIN teacher_profiles tp ON u.id = tp.user_id
                WHERE c.slug = :slug
                LIMIT 1";
        
        return $this->query($sql, ['slug' => $slug])->fetch();
    }

    /**
     * Get course lessons
     */
    public function getLessons($courseId) {
        $sql = "SELECT * FROM lessons 
                WHERE course_id = :course_id AND is_published = 1
                ORDER BY order_index ASC";
        
        return $this->query($sql, ['course_id' => $courseId])->fetchAll();
    }

    /**
     * Get student courses
     */
    public function getStudentCourses($studentId) {
        $sql = "SELECT c.*, e.progress, e.enrolled_at, e.status as enrollment_status,
                s.name as subject_name, u.full_name as teacher_name
                FROM enrollments e
                INNER JOIN {$this->table} c ON e.course_id = c.id
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE e.student_id = :student_id
                ORDER BY e.enrolled_at DESC";
        
        return $this->query($sql, ['student_id' => $studentId])->fetchAll();
    }

    /**
     * Get teacher courses
     */
    public function getTeacherCourses($teacherId) {
        $sql = "SELECT c.*, s.name as subject_name,
                COUNT(DISTINCT e.id) as total_students
                FROM {$this->table} c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
                WHERE c.teacher_id = :teacher_id
                GROUP BY c.id
                ORDER BY c.created_at DESC";
        
        return $this->query($sql, ['teacher_id' => $teacherId])->fetchAll();
    }

    /**
     * Check if student enrolled
     */
    public function isStudentEnrolled($courseId, $studentId) {
        $sql = "SELECT COUNT(*) as count FROM enrollments 
                WHERE course_id = :course_id AND student_id = :student_id";
        
        $result = $this->query($sql, [
            'course_id' => $courseId,
            'student_id' => $studentId
        ])->fetch();
        
        return $result['count'] > 0;
    }

    /**
     * Enroll student
     */
    public function enrollStudent($courseId, $studentId) {
        $sql = "INSERT INTO enrollments (course_id, student_id) VALUES (:course_id, :student_id)";
        
        $result = $this->query($sql, [
            'course_id' => $courseId,
            'student_id' => $studentId
        ]);
        
        // Update enrollment count
        if ($result) {
            $this->query("UPDATE {$this->table} SET enrollment_count = enrollment_count + 1 WHERE id = :id", 
                ['id' => $courseId]);
        }
        
        return $result;
    }

    /**
     * Get course progress
     */
    public function getCourseProgress($courseId, $studentId) {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM lessons WHERE course_id = :course_id AND is_published = 1) as total_lessons,
                (SELECT COUNT(*) FROM lesson_progress lp 
                 INNER JOIN lessons l ON lp.lesson_id = l.id
                 WHERE l.course_id = :course_id AND lp.student_id = :student_id AND lp.completed = 1) as completed_lessons";
        
        $result = $this->query($sql, [
            'course_id' => $courseId,
            'student_id' => $studentId
        ])->fetch();
        
        if ($result['total_lessons'] > 0) {
            return ($result['completed_lessons'] / $result['total_lessons']) * 100;
        }
        
        return 0;
    }

    /**
     * Search courses
     */
    public function search($keyword, $filters = []) {
        $sql = "SELECT c.*, s.name as subject_name, u.full_name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN subjects s ON c.subject_id = s.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1 AND (c.title LIKE :keyword OR c.description LIKE :keyword)";
        
        $params = ['keyword' => "%{$keyword}%"];
        
        if (!empty($filters['subject_id'])) {
            $sql .= " AND c.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }
        
        if (!empty($filters['level'])) {
            $sql .= " AND c.level = :level";
            $params['level'] = $filters['level'];
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Increment views
     */
    public function incrementViews($courseId) {
        $sql = "UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = :id";
        return $this->query($sql, ['id' => $courseId]);
    }
}
