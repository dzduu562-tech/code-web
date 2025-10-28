<?php

class Course {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function find($id) {
        $sql = "SELECT c.*, u.name as teacher_name, u.email as teacher_email
                FROM courses c
                JOIN users u ON c.teacher_id = u.id
                WHERE c.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getAll($filters = []) {
        $where = "c.is_published = 1";
        $params = [];

        if (!empty($filters['teacher_id'])) {
            $where .= " AND c.teacher_id = ?";
            $params[] = $filters['teacher_id'];
        }

        if (!empty($filters['subject'])) {
            $where .= " AND c.subject = ?";
            $params[] = $filters['subject'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (c.title LIKE ? OR c.description LIKE ? OR u.name LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (isset($filters['is_published'])) {
            $where = str_replace("c.is_published = 1", "c.is_published = " . (int)$filters['is_published'], $where);
        }

        $sql = "SELECT c.*, u.name as teacher_name,
                       (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) as student_count,
                       (SELECT COUNT(*) FROM chapters ch WHERE ch.course_id = c.id) as chapter_count
                FROM courses c
                JOIN users u ON c.teacher_id = u.id
                WHERE {$where}
                ORDER BY c.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function count($filters = []) {
        $where = "c.is_published = 1";
        $params = [];

        if (!empty($filters['teacher_id'])) {
            $where .= " AND c.teacher_id = ?";
            $params[] = $filters['teacher_id'];
        }

        if (!empty($filters['subject'])) {
            $where .= " AND c.subject = ?";
            $params[] = $filters['subject'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (c.title LIKE ? OR c.description LIKE ? OR u.name LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (isset($filters['is_published'])) {
            $where = str_replace("c.is_published = 1", "c.is_published = " . (int)$filters['is_published'], $where);
        }

        $sql = "SELECT COUNT(*) as count FROM courses c JOIN users u ON c.teacher_id = u.id WHERE {$where}";
        $result = $this->db->fetch($sql, $params);
        return (int) $result['count'];
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('courses', $data);
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('courses', $data, 'id = ?', [$id]);
    }

    public function delete($id) {
        // Delete related data first (cascade should handle this, but let's be explicit)
        $this->db->delete('enrollments', 'course_id = ?', [$id]);
        $this->db->delete('assignments', 'course_id = ?', [$id]);
        $this->db->delete('forum_threads', 'course_id = ?', [$id]);
        
        return $this->db->delete('courses', 'id = ?', [$id]);
    }

    public function publish($id) {
        return $this->update($id, ['is_published' => 1]);
    }

    public function unpublish($id) {
        return $this->update($id, ['is_published' => 0]);
    }

    public function getChapters($courseId) {
        $sql = "SELECT ch.*,
                       (SELECT COUNT(*) FROM lessons l WHERE l.chapter_id = ch.id) as lesson_count
                FROM chapters ch
                WHERE ch.course_id = ?
                ORDER BY ch.position ASC";
        return $this->db->fetchAll($sql, [$courseId]);
    }

    public function getChapterWithLessons($chapterId) {
        $chapter = $this->db->fetch("SELECT * FROM chapters WHERE id = ?", [$chapterId]);
        if ($chapter) {
            $chapter['lessons'] = $this->db->fetchAll(
                "SELECT * FROM lessons WHERE chapter_id = ? ORDER BY position ASC",
                [$chapterId]
            );
        }
        return $chapter;
    }

    public function getAllLessons($courseId) {
        $sql = "SELECT l.*, ch.title as chapter_title, ch.position as chapter_position
                FROM lessons l
                JOIN chapters ch ON l.chapter_id = ch.id
                WHERE ch.course_id = ?
                ORDER BY ch.position ASC, l.position ASC";
        return $this->db->fetchAll($sql, [$courseId]);
    }

    public function getEnrolledStudents($courseId) {
        $sql = "SELECT u.*, e.progress_percent, e.enrolled_at, e.last_view_at
                FROM users u
                JOIN enrollments e ON u.id = e.user_id
                WHERE e.course_id = ?
                ORDER BY e.enrolled_at DESC";
        return $this->db->fetchAll($sql, [$courseId]);
    }

    public function isEnrolled($courseId, $userId) {
        return $this->db->exists('enrollments', 'course_id = ? AND user_id = ?', [$courseId, $userId]);
    }

    public function enroll($courseId, $userId) {
        if ($this->isEnrolled($courseId, $userId)) {
            return false;
        }

        $enrollmentId = $this->db->insert('enrollments', [
            'course_id' => $courseId,
            'user_id' => $userId,
            'enrolled_at' => date('Y-m-d H:i:s')
        ]);

        // Send notification
        $course = $this->find($courseId);
        Helpers::sendNotification(
            $userId,
            'enrollment',
            'Đăng ký khóa học thành công',
            "Bạn đã đăng ký thành công khóa học: {$course['title']}",
            ['course_id' => $courseId]
        );

        return $enrollmentId;
    }

    public function unenroll($courseId, $userId) {
        return $this->db->delete('enrollments', 'course_id = ? AND user_id = ?', [$courseId, $userId]);
    }

    public function updateProgress($courseId, $userId) {
        // Calculate progress based on completed lessons
        $totalLessons = $this->db->count(
            'lessons l JOIN chapters ch ON l.chapter_id = ch.id',
            'ch.course_id = ?',
            [$courseId]
        );

        if ($totalLessons == 0) {
            return;
        }

        $completedLessons = $this->db->fetch(
            "SELECT COUNT(*) as count
             FROM lesson_progress lp
             JOIN lessons l ON lp.lesson_id = l.id
             JOIN chapters ch ON l.chapter_id = ch.id
             WHERE ch.course_id = ? AND lp.user_id = ? AND lp.is_completed = 1",
            [$courseId, $userId]
        )['count'];

        $progress = ($completedLessons / $totalLessons) * 100;

        $this->db->update('enrollments', [
            'progress_percent' => $progress,
            'last_view_at' => date('Y-m-d H:i:s')
        ], 'course_id = ? AND user_id = ?', [$courseId, $userId]);

        return $progress;
    }

    public function getProgress($courseId, $userId) {
        $enrollment = $this->db->fetch(
            "SELECT progress_percent FROM enrollments WHERE course_id = ? AND user_id = ?",
            [$courseId, $userId]
        );
        return $enrollment ? $enrollment['progress_percent'] : 0;
    }

    public function getAssignments($courseId) {
        $sql = "SELECT a.*,
                       (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id) as submission_count
                FROM assignments a
                WHERE a.course_id = ?
                ORDER BY a.created_at DESC";
        return $this->db->fetchAll($sql, [$courseId]);
    }

    public function getForumThreads($courseId, $lessonId = null) {
        $where = "ft.course_id = ?";
        $params = [$courseId];

        if ($lessonId) {
            $where .= " AND ft.lesson_id = ?";
            $params[] = $lessonId;
        }

        $sql = "SELECT ft.*, u.name as author_name,
                       (SELECT COUNT(*) FROM forum_posts fp WHERE fp.thread_id = ft.id) as post_count,
                       (SELECT MAX(fp.created_at) FROM forum_posts fp WHERE fp.thread_id = ft.id) as last_post_at
                FROM forum_threads ft
                JOIN users u ON ft.author_id = u.id
                WHERE {$where}
                ORDER BY ft.is_pinned DESC, ft.updated_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getSubjects() {
        $sql = "SELECT DISTINCT subject FROM courses WHERE is_published = 1 ORDER BY subject";
        $results = $this->db->fetchAll($sql);
        return array_column($results, 'subject');
    }

    public function getPopularCourses($limit = 5) {
        $sql = "SELECT c.*, u.name as teacher_name, COUNT(e.id) as student_count
                FROM courses c
                JOIN users u ON c.teacher_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                WHERE c.is_published = 1
                GROUP BY c.id
                ORDER BY student_count DESC, c.created_at DESC
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function getRecentCourses($limit = 5) {
        $sql = "SELECT c.*, u.name as teacher_name
                FROM courses c
                JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1
                ORDER BY c.created_at DESC
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function getStats($courseId = null) {
        if ($courseId) {
            // Individual course stats
            $course = $this->find($courseId);
            if (!$course) return null;

            return [
                'student_count' => $this->db->count('enrollments', 'course_id = ?', [$courseId]),
                'chapter_count' => $this->db->count('chapters', 'course_id = ?', [$courseId]),
                'lesson_count' => $this->db->fetch(
                    "SELECT COUNT(*) as count FROM lessons l JOIN chapters ch ON l.chapter_id = ch.id WHERE ch.course_id = ?",
                    [$courseId]
                )['count'],
                'assignment_count' => $this->db->count('assignments', 'course_id = ?', [$courseId]),
                'forum_thread_count' => $this->db->count('forum_threads', 'course_id = ?', [$courseId]),
                'avg_progress' => $this->db->fetch(
                    "SELECT AVG(progress_percent) as avg FROM enrollments WHERE course_id = ?",
                    [$courseId]
                )['avg'] ?? 0
            ];
        } else {
            // System-wide course stats
            return [
                'total_courses' => $this->db->count('courses'),
                'published_courses' => $this->db->count('courses', 'is_published = 1'),
                'total_enrollments' => $this->db->count('enrollments'),
                'total_chapters' => $this->db->count('chapters'),
                'total_lessons' => $this->db->count('lessons'),
                'new_courses_today' => $this->db->count('courses', 'DATE(created_at) = CURDATE()'),
                'new_courses_week' => $this->db->count('courses', 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ];
        }
    }
}