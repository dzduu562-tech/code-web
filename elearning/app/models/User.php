<?php
namespace Models;

use PDO;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $st = $this->db->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $st->execute([$email]);
        $u = $st->fetch(PDO::FETCH_ASSOC);
        return $u ?: null;
    }

    public function create(string $name, string $email, string $passwordHash, string $role): int
    {
        $st = $this->db->prepare('INSERT INTO users(name,email,password_hash,role,created_at) VALUES(?,?,?,?,NOW())');
        $st->execute([$name,$email,$passwordHash,$role]);
        return (int)$this->db->lastInsertId();
    }
}
