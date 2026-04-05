<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class StudentTest extends TestCase
{
    private PDO $pdoMock;

    private Student $student;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->student = new Student($this->pdoMock);
    }

    public function testSaveInsertsStudent(): void
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute')->with(['Ivan'])->willReturn(true);
        $this->pdoMock->expects($this->once())->method('prepare')->with('INSERT INTO students (name) VALUES (?)')->willReturn($stmtMock);
        $this->assertTrue($this->student->save('Ivan'));
    }

    public function testGetAllReturnsRows(): void
    {
        $expected = [['id' => 1, 'name' => 'Ivan', 'timestamp' => '2020-01-01 00:00:00']];
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('fetchAll')->willReturn($expected);
        $this->pdoMock->expects($this->once())->method('query')->with('SELECT * FROM students ORDER BY timestamp DESC')->willReturn($stmtMock);
        $this->assertEquals($expected, $this->student->getAll());
    }

    public function testSaveWithMock(): void
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute')->with(['Ivan'])->willReturn(true);
        $this->pdoMock->expects($this->once())->method('prepare')->with('INSERT INTO students (name) VALUES (?)')->willReturn($stmtMock);
        $this->assertTrue($this->student->save('Ivan'));
    }
}
