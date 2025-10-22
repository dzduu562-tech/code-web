<?php
namespace Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $pdo = null;

    public static function conn(array $config): PDO
    {
        if (self::$pdo === null) {
            $host = $config['db']['host'];
            $db   = $config['db']['name'];
            $user = $config['db']['user'];
            $pass = $config['db']['pass'];
            $charset = $config['db']['charset'] ?? 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                echo 'Database connection failed.';
                exit;
            }
        }
        return self::$pdo;
    }
}
