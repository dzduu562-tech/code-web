<?php
/**
 * Subject Model
 */

class Subject extends Model {
    protected $table = 'subjects';

    /**
     * Get active subjects
     */
    public function getActiveSubjects() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Create subject
     */
    public function create($data) {
        return $this->insert($data);
    }
}
