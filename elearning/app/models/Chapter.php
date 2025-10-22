<?php

class Chapter {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM chapters WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('chapters', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('chapters', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('chapters', 'id = :id', ['id' => $id]);
    }
    
    public function getByCourse($courseId) {
        $sql = "SELECT * FROM chapters WHERE course_id = :course_id ORDER BY position ASC";
        return $this->db->fetchAll($sql, ['course_id' => $courseId]);
    }
    
    public function getWithLessons($courseId) {
        $chapters = $this->getByCourse($courseId);
        $lessonModel = new Lesson();
        
        foreach ($chapters as &$chapter) {
            $chapter['lessons'] = $lessonModel->getByChapter($chapter['id']);
        }
        
        return $chapters;
    }
}
