<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('integration')]
class StudentIntegrationTest extends TestCase
{
    private function createPdo(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db = $_ENV['DB_NAME'] ?? 'test_db';
        $user = $_ENV['DB_USER'] ?? 'test_user';
        $pass = $_ENV['DB_PASSWORD'] ?? 'test_pass';
        $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $pass, $options);
    }

    protected function setUp(): void
    {
        $pdo = $this->createPdo();
        $pdo->exec('DROP TABLE IF EXISTS students');
        $student = new Student($pdo);
        $student->createTable();
    }

    private function student(): Student
    {
        return new Student(null);
    }

    public function testSaveAndGetAllRowCount(): void
    {
        $student = $this->student();
        $this->assertTrue($student->save('Ann'));
        $this->assertTrue($student->save('Bob'));
        $rows = $student->getAll();
        $this->assertCount(2, $rows);
        $names = array_column($rows, 'name');
        $this->assertContains('Ann', $names);
        $this->assertContains('Bob', $names);
    }

    public function testInvalidDatabasePasswordThrows(): void
    {
        $this->expectException(PDOException::class);
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db = $_ENV['DB_NAME'] ?? 'test_db';
        $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        new PDO($dsn, 'test_user', 'wrong_password', $options);
    }
}
