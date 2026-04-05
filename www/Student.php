<?php

class Student
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo !== null) {
            $this->pdo = $pdo;
        } else {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $db = $_ENV['DB_NAME'] ?? 'test_db';
            $user = $_ENV['DB_USER'] ?? 'test_user';
            $pass = $_ENV['DB_PASSWORD'] ?? 'test_pass';
            $charset = 'utf8mb4';
            $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        }
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function save(string $name): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO students (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM students ORDER BY timestamp DESC');
        return $stmt->fetchAll();
    }
}
