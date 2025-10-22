<?php

class Lesson {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function find($id) {
        $sql = "SELECT l.*, ch.title as chapter_title, ch.course_id,
                       c.title as course_title, c.teacher_id
                FROM lessons l
                JOIN chapters ch ON l.chapter_id = ch.id
                JOIN courses c ON ch.course_id = c.id
                WHERE l.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getAll($filters = []) {
        $where = "l.is_published = 1";
        $params = [];

        if (!empty($filters['chapter_id'])) {
            $where .= " AND l.chapter_id = ?";
            $params[] = $filters['chapter_id'];
        }

        if (!empty($filters['course_id'])) {
            $where .= " AND ch.course_id = ?";
            $params[] = $filters['course_id'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (l.title LIKE ? OR l.content_html LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (isset($filters['is_published'])) {
            $where = str_replace("l.is_published = 1", "l.is_published = " . (int)$filters['is_published'], $where);
        }

        $sql = "SELECT l.*, ch.title as chapter_title, ch.course_id,
                       c.title as course_title
                FROM lessons l
                JOIN chapters ch ON l.chapter_id = ch.id
                JOIN courses c ON ch.course_id = c.id
                WHERE {$where}
                ORDER BY ch.position ASC, l.position ASC";

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
        
        // Auto-set position if not provided
        if (!isset($data['position'])) {
            $maxPosition = $this->db->fetch(
                "SELECT MAX(position) as max_pos FROM lessons WHERE chapter_id = ?",
                [$data['chapter_id']]
            );
            $data['position'] = ($maxPosition['max_pos'] ?? 0) + 1;
        }

        $lessonId = $this->db->insert('lessons', $data);

        // Notify enrolled students about new lesson
        if ($data['is_published'] ?? false) {
            $this->notifyNewLesson($lessonId);
        }

        return $lessonId;
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $wasPublished = $this->db->fetch("SELECT is_published FROM lessons WHERE id = ?", [$id])['is_published'];
        $result = $this->db->update('lessons', $data, 'id = ?', [$id]);

        // Notify if lesson is newly published
        if (!$wasPublished && ($data['is_published'] ?? false)) {
            $this->notifyNewLesson($id);
        }

        return $result;
    }

    public function delete($id) {
        // Delete related data
        $this->db->delete('resources', 'lesson_id = ?', [$id]);
        $this->db->delete('lesson_progress', 'lesson_id = ?', [$id]);
        $this->db->delete('forum_threads', 'lesson_id = ?', [$id]);
        
        // Delete quizzes and related data
        $quizzes = $this->db->fetchAll("SELECT id FROM quizzes WHERE lesson_id = ?", [$id]);
        foreach ($quizzes as $quiz) {
            $this->deleteQuiz($quiz['id']);
        }
        
        return $this->db->delete('lessons', 'id = ?', [$id]);
    }

    private function deleteQuiz($quizId) {
        // Delete quiz attempts and answers
        $attempts = $this->db->fetchAll("SELECT id FROM quiz_attempts WHERE quiz_id = ?", [$quizId]);
        foreach ($attempts as $attempt) {
            $this->db->delete('answers', 'attempt_id = ?', [$attempt['id']]);
        }
        $this->db->delete('quiz_attempts', 'quiz_id = ?', [$quizId]);
        
        // Delete questions and options
        $questions = $this->db->fetchAll("SELECT id FROM questions WHERE quiz_id = ?", [$quizId]);
        foreach ($questions as $question) {
            $this->db->delete('options', 'question_id = ?', [$question['id']]);
        }
        $this->db->delete('questions', 'quiz_id = ?', [$quizId]);
        
        // Delete quiz
        $this->db->delete('quizzes', 'id = ?', [$quizId]);
    }

    public function publish($id) {
        $result = $this->update($id, ['is_published' => 1]);
        if ($result) {
            $this->notifyNewLesson($id);
        }
        return $result;
    }

    public function unpublish($id) {
        return $this->update($id, ['is_published' => 0]);
    }

    public function getResources($lessonId) {
        return $this->db->fetchAll(
            "SELECT * FROM resources WHERE lesson_id = ? ORDER BY created_at ASC",
            [$lessonId]
        );
    }

    public function addResource($lessonId, $data) {
        $data['lesson_id'] = $lessonId;
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('resources', $data);
    }

    public function deleteResource($resourceId) {
        $resource = $this->db->fetch("SELECT * FROM resources WHERE id = ?", [$resourceId]);
        if ($resource) {
            // Delete physical file
            Helpers::deleteFile($resource['file_path']);
            // Delete database record
            return $this->db->delete('resources', 'id = ?', [$resourceId]);
        }
        return false;
    }

    public function getProgress($lessonId, $userId) {
        return $this->db->fetch(
            "SELECT * FROM lesson_progress WHERE lesson_id = ? AND user_id = ?",
            [$lessonId, $userId]
        );
    }

    public function markAsCompleted($lessonId, $userId, $timeSpent = 0) {
        $existing = $this->getProgress($lessonId, $userId);
        
        if ($existing) {
            $this->db->update('lesson_progress', [
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s'),
                'time_spent_minutes' => $existing['time_spent_minutes'] + $timeSpent
            ], 'id = ?', [$existing['id']]);
        } else {
            $this->db->insert('lesson_progress', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s'),
                'time_spent_minutes' => $timeSpent
            ]);
        }

        // Update course progress
        $lesson = $this->find($lessonId);
        if ($lesson) {
            $courseModel = new Course();
            $courseModel->updateProgress($lesson['course_id'], $userId);
        }
    }

    public function markAsIncomplete($lessonId, $userId) {
        $this->db->update('lesson_progress', [
            'is_completed' => 0,
            'completed_at' => null
        ], 'lesson_id = ? AND user_id = ?', [$lessonId, $userId]);

        // Update course progress
        $lesson = $this->find($lessonId);
        if ($lesson) {
            $courseModel = new Course();
            $courseModel->updateProgress($lesson['course_id'], $userId);
        }
    }

    public function updateTimeSpent($lessonId, $userId, $timeSpent) {
        $existing = $this->getProgress($lessonId, $userId);
        
        if ($existing) {
            $this->db->update('lesson_progress', [
                'time_spent_minutes' => $existing['time_spent_minutes'] + $timeSpent
            ], 'id = ?', [$existing['id']]);
        } else {
            $this->db->insert('lesson_progress', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'time_spent_minutes' => $timeSpent
            ]);
        }
    }

    public function getQuizzes($lessonId) {
        return $this->db->fetchAll(
            "SELECT q.*, 
                    (SELECT COUNT(*) FROM questions qu WHERE qu.quiz_id = q.id) as question_count
             FROM quizzes q 
             WHERE q.lesson_id = ? AND q.is_published = 1
             ORDER BY q.created_at ASC",
            [$lessonId]
        );
    }

    public function getNextLesson($lessonId, $userId = null) {
        $currentLesson = $this->find($lessonId);
        if (!$currentLesson) return null;

        // Try to find next lesson in same chapter
        $nextLesson = $this->db->fetch(
            "SELECT * FROM lessons 
             WHERE chapter_id = ? AND position > ? AND is_published = 1 
             ORDER BY position ASC LIMIT 1",
            [$currentLesson['chapter_id'], $currentLesson['position']]
        );

        if ($nextLesson) {
            return $nextLesson;
        }

        // Try to find first lesson in next chapter
        $nextChapter = $this->db->fetch(
            "SELECT * FROM chapters 
             WHERE course_id = ? AND position > ? 
             ORDER BY position ASC LIMIT 1",
            [$currentLesson['course_id'], $currentLesson['chapter_position'] ?? 0]
        );

        if ($nextChapter) {
            return $this->db->fetch(
                "SELECT * FROM lessons 
                 WHERE chapter_id = ? AND is_published = 1 
                 ORDER BY position ASC LIMIT 1",
                [$nextChapter['id']]
            );
        }

        return null;
    }

    public function getPreviousLesson($lessonId, $userId = null) {
        $currentLesson = $this->find($lessonId);
        if (!$currentLesson) return null;

        // Try to find previous lesson in same chapter
        $prevLesson = $this->db->fetch(
            "SELECT * FROM lessons 
             WHERE chapter_id = ? AND position < ? AND is_published = 1 
             ORDER BY position DESC LIMIT 1",
            [$currentLesson['chapter_id'], $currentLesson['position']]
        );

        if ($prevLesson) {
            return $prevLesson;
        }

        // Try to find last lesson in previous chapter
        $prevChapter = $this->db->fetch(
            "SELECT * FROM chapters 
             WHERE course_id = ? AND position < ? 
             ORDER BY position DESC LIMIT 1",
            [$currentLesson['course_id'], $currentLesson['chapter_position'] ?? 999]
        );

        if ($prevChapter) {
            return $this->db->fetch(
                "SELECT * FROM lessons 
                 WHERE chapter_id = ? AND is_published = 1 
                 ORDER BY position DESC LIMIT 1",
                [$prevChapter['id']]
            );
        }

        return null;
    }

    public function reorderLessons($chapterId, $lessonIds) {
        $this->db->beginTransaction();
        
        try {
            foreach ($lessonIds as $position => $lessonId) {
                $this->db->update('lessons', 
                    ['position' => $position + 1], 
                    'id = ? AND chapter_id = ?', 
                    [$lessonId, $chapterId]
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    private function notifyNewLesson($lessonId) {
        $lesson = $this->find($lessonId);
        if (!$lesson) return;

        // Get all enrolled students
        $students = $this->db->fetchAll(
            "SELECT e.user_id FROM enrollments e WHERE e.course_id = ?",
            [$lesson['course_id']]
        );

        foreach ($students as $student) {
            Helpers::sendNotification(
                $student['user_id'],
                'new_lesson',
                'Bài học mới',
                "Bài học mới '{$lesson['title']}' đã được thêm vào khóa học '{$lesson['course_title']}'",
                [
                    'lesson_id' => $lessonId,
                    'course_id' => $lesson['course_id']
                ]
            );
        }
    }

    public function getStats($lessonId = null) {
        if ($lessonId) {
            // Individual lesson stats
            $lesson = $this->find($lessonId);
            if (!$lesson) return null;

            return [
                'view_count' => $this->db->count('lesson_progress', 'lesson_id = ?', [$lessonId]),
                'completion_count' => $this->db->count('lesson_progress', 'lesson_id = ? AND is_completed = 1', [$lessonId]),
                'avg_time_spent' => $this->db->fetch(
                    "SELECT AVG(time_spent_minutes) as avg FROM lesson_progress WHERE lesson_id = ?",
                    [$lessonId]
                )['avg'] ?? 0,
                'resource_count' => $this->db->count('resources', 'lesson_id = ?', [$lessonId]),
                'quiz_count' => $this->db->count('quizzes', 'lesson_id = ?', [$lessonId])
            ];
        } else {
            // System-wide lesson stats
            return [
                'total_lessons' => $this->db->count('lessons'),
                'published_lessons' => $this->db->count('lessons', 'is_published = 1'),
                'total_completions' => $this->db->count('lesson_progress', 'is_completed = 1'),
                'total_resources' => $this->db->count('resources'),
                'new_lessons_today' => $this->db->count('lessons', 'DATE(created_at) = CURDATE()'),
                'new_lessons_week' => $this->db->count('lessons', 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')
            ];
        }
    }
}