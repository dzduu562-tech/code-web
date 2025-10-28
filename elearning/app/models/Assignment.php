<?php

class Assignment {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function find($id) {
        $sql = "SELECT a.*, c.title as course_title, c.teacher_id,
                       (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id) as submission_count
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                WHERE a.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getAll($filters = []) {
        $where = "1=1";
        $params = [];

        if (!empty($filters['course_id'])) {
            $where .= " AND a.course_id = ?";
            $params[] = $filters['course_id'];
        }

        if (!empty($filters['teacher_id'])) {
            $where .= " AND c.teacher_id = ?";
            $params[] = $filters['teacher_id'];
        }

        if (isset($filters['is_published'])) {
            $where .= " AND a.is_published = ?";
            $params[] = $filters['is_published'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (a.title LIKE ? OR a.description LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        $sql = "SELECT a.*, c.title as course_title,
                       (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id) as submission_count
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                WHERE {$where}
                ORDER BY a.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $assignmentId = $this->db->insert('assignments', $data);

        // Notify enrolled students if published
        if ($data['is_published'] ?? false) {
            $this->notifyNewAssignment($assignmentId);
        }

        return $assignmentId;
    }

    public function update($id, $data) {
        $wasPublished = $this->db->fetch("SELECT is_published FROM assignments WHERE id = ?", [$id])['is_published'];
        $result = $this->db->update('assignments', $data, 'id = ?', [$id]);

        // Notify if newly published
        if (!$wasPublished && ($data['is_published'] ?? false)) {
            $this->notifyNewAssignment($id);
        }

        return $result;
    }

    public function delete($id) {
        // Delete submissions and their files
        $submissions = $this->db->fetchAll("SELECT * FROM submissions WHERE assignment_id = ?", [$id]);
        foreach ($submissions as $submission) {
            if ($submission['file_path']) {
                Helpers::deleteFile($submission['file_path']);
            }
        }
        
        $this->db->delete('submissions', 'assignment_id = ?', [$id]);
        return $this->db->delete('assignments', 'id = ?', [$id]);
    }

    public function publish($id) {
        $result = $this->update($id, ['is_published' => 1]);
        if ($result) {
            $this->notifyNewAssignment($id);
        }
        return $result;
    }

    public function unpublish($id) {
        return $this->update($id, ['is_published' => 0]);
    }

    public function getSubmissions($assignmentId, $filters = []) {
        $where = "s.assignment_id = ?";
        $params = [$assignmentId];

        if (!empty($filters['student_id'])) {
            $where .= " AND s.student_id = ?";
            $params[] = $filters['student_id'];
        }

        if (isset($filters['graded'])) {
            if ($filters['graded']) {
                $where .= " AND s.score IS NOT NULL";
            } else {
                $where .= " AND s.score IS NULL";
            }
        }

        $sql = "SELECT s.*, u.name as student_name, u.email as student_email
                FROM submissions s
                JOIN users u ON s.student_id = u.id
                WHERE {$where}
                ORDER BY s.submitted_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getSubmission($assignmentId, $studentId) {
        $sql = "SELECT s.*, u.name as student_name, u.email as student_email
                FROM submissions s
                JOIN users u ON s.student_id = u.id
                WHERE s.assignment_id = ? AND s.student_id = ?";
        return $this->db->fetch($sql, [$assignmentId, $studentId]);
    }

    public function submitAssignment($assignmentId, $studentId, $data) {
        // Check if assignment exists and is published
        $assignment = $this->find($assignmentId);
        if (!$assignment || !$assignment['is_published']) {
            return ['success' => false, 'message' => 'Bài tập không khả dụng'];
        }

        // Check if due date has passed
        if ($assignment['due_at'] && strtotime($assignment['due_at']) < time()) {
            return ['success' => false, 'message' => 'Đã quá hạn nộp bài'];
        }

        // Check if student is enrolled
        $courseModel = new Course();
        if (!$courseModel->isEnrolled($assignment['course_id'], $studentId)) {
            return ['success' => false, 'message' => 'Bạn chưa đăng ký khóa học này'];
        }

        try {
            $submissionData = [
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'note' => $data['note'] ?? '',
                'submitted_at' => date('Y-m-d H:i:s')
            ];

            if (!empty($data['file_path'])) {
                $submissionData['file_path'] = $data['file_path'];
            }

            if (!empty($data['submission_url'])) {
                $submissionData['submission_url'] = $data['submission_url'];
            }

            // Check if submission already exists
            $existingSubmission = $this->getSubmission($assignmentId, $studentId);
            
            if ($existingSubmission) {
                // Update existing submission
                unset($submissionData['assignment_id']);
                unset($submissionData['student_id']);
                
                // Delete old file if new one is uploaded
                if (!empty($data['file_path']) && $existingSubmission['file_path']) {
                    Helpers::deleteFile($existingSubmission['file_path']);
                }
                
                $this->db->update('submissions', $submissionData, 
                    'assignment_id = ? AND student_id = ?', 
                    [$assignmentId, $studentId]
                );
                
                $submissionId = $existingSubmission['id'];
            } else {
                // Create new submission
                $submissionId = $this->db->insert('submissions', $submissionData);
            }

            return ['success' => true, 'submission_id' => $submissionId];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi nộp bài'];
        }
    }

    public function gradeSubmission($submissionId, $score, $feedback = '') {
        try {
            $submission = $this->db->fetch("SELECT * FROM submissions WHERE id = ?", [$submissionId]);
            if (!$submission) {
                return ['success' => false, 'message' => 'Bài nộp không tồn tại'];
            }

            $this->db->update('submissions', [
                'score' => $score,
                'feedback' => $feedback,
                'graded_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$submissionId]);

            // Send notification to student
            $assignment = $this->find($submission['assignment_id']);
            Helpers::sendNotification(
                $submission['student_id'],
                'assignment_graded',
                'Bài tập đã được chấm điểm',
                "Bài tập '{$assignment['title']}' của bạn đã được chấm điểm: {$score}/{$assignment['max_score']}",
                [
                    'assignment_id' => $submission['assignment_id'],
                    'submission_id' => $submissionId,
                    'score' => $score
                ]
            );

            return ['success' => true, 'message' => 'Chấm điểm thành công'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi chấm điểm'];
        }
    }

    public function getStudentAssignments($studentId, $courseId = null) {
        $where = "c.is_published = 1 AND a.is_published = 1";
        $params = [];

        if ($courseId) {
            $where .= " AND a.course_id = ?";
            $params[] = $courseId;
        }

        // Only get assignments from enrolled courses
        $where .= " AND EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = a.course_id AND e.user_id = ?)";
        $params[] = $studentId;

        $sql = "SELECT a.*, c.title as course_title,
                       s.id as submission_id, s.submitted_at, s.score, s.feedback, s.graded_at,
                       CASE 
                           WHEN a.due_at IS NULL THEN 0
                           WHEN a.due_at < NOW() THEN 1 
                           ELSE 0 
                       END as is_overdue
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
                WHERE {$where}
                ORDER BY a.due_at ASC, a.created_at DESC";

        $params[] = $studentId;
        return $this->db->fetchAll($sql, $params);
    }

    public function getTeacherAssignments($teacherId, $courseId = null) {
        $where = "c.teacher_id = ?";
        $params = [$teacherId];

        if ($courseId) {
            $where .= " AND a.course_id = ?";
            $params[] = $courseId;
        }

        $sql = "SELECT a.*, c.title as course_title,
                       (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id) as total_submissions,
                       (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id AND s.score IS NOT NULL) as graded_submissions,
                       (SELECT COUNT(DISTINCT e.user_id) FROM enrollments e WHERE e.course_id = a.course_id) as enrolled_students
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                WHERE {$where}
                ORDER BY a.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getAssignmentStats($assignmentId) {
        $assignment = $this->find($assignmentId);
        if (!$assignment) return null;

        $totalStudents = $this->db->count(
            'enrollments', 
            'course_id = ?', 
            [$assignment['course_id']]
        );

        $totalSubmissions = $this->db->count(
            'submissions', 
            'assignment_id = ?', 
            [$assignmentId]
        );

        $gradedSubmissions = $this->db->count(
            'submissions', 
            'assignment_id = ? AND score IS NOT NULL', 
            [$assignmentId]
        );

        $avgScore = $this->db->fetch(
            "SELECT AVG(score) as avg_score FROM submissions WHERE assignment_id = ? AND score IS NOT NULL",
            [$assignmentId]
        )['avg_score'] ?? 0;

        $onTimeSubmissions = $this->db->count(
            'submissions', 
            'assignment_id = ? AND (? IS NULL OR submitted_at <= ?)', 
            [$assignmentId, $assignment['due_at'], $assignment['due_at']]
        );

        return [
            'total_students' => $totalStudents,
            'total_submissions' => $totalSubmissions,
            'graded_submissions' => $gradedSubmissions,
            'submission_rate' => $totalStudents > 0 ? ($totalSubmissions / $totalStudents) * 100 : 0,
            'grading_progress' => $totalSubmissions > 0 ? ($gradedSubmissions / $totalSubmissions) * 100 : 0,
            'average_score' => $avgScore,
            'on_time_submissions' => $onTimeSubmissions,
            'late_submissions' => $totalSubmissions - $onTimeSubmissions
        ];
    }

    public function getUpcomingAssignments($userId, $limit = 5) {
        $sql = "SELECT a.*, c.title as course_title,
                       s.id as submission_id, s.submitted_at
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
                WHERE e.user_id = ? AND a.is_published = 1 
                      AND (a.due_at IS NULL OR a.due_at > NOW())
                      AND s.id IS NULL
                ORDER BY a.due_at ASC
                LIMIT ?";

        return $this->db->fetchAll($sql, [$userId, $userId, $limit]);
    }

    private function notifyNewAssignment($assignmentId) {
        $assignment = $this->find($assignmentId);
        if (!$assignment) return;

        // Get all enrolled students
        $students = $this->db->fetchAll(
            "SELECT e.user_id FROM enrollments e WHERE e.course_id = ?",
            [$assignment['course_id']]
        );

        foreach ($students as $student) {
            Helpers::sendNotification(
                $student['user_id'],
                'new_assignment',
                'Bài tập mới',
                "Bài tập mới '{$assignment['title']}' đã được giao trong khóa học '{$assignment['course_title']}'",
                [
                    'assignment_id' => $assignmentId,
                    'course_id' => $assignment['course_id'],
                    'due_at' => $assignment['due_at']
                ]
            );
        }
    }
}