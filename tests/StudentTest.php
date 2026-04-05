<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class StudentTest extends TestCase
{
    public function testSaveInsertsStudent(): void
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('execute')->with(['Ivan'])->willReturn(true);
        $pdoMock->expects($this->once())->method('prepare')->with('INSERT INTO students (name) VALUES (?)')->willReturn($stmtMock);
        $student = new Student($pdoMock);
        $this->assertTrue($student->save('Ivan'));
    }

    public function testGetAllReturnsRows(): void
    {
        $expected = [['id' => 1, 'name' => 'Ivan', 'timestamp' => '2020-01-01 00:00:00']];
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())->method('fetchAll')->willReturn($expected);
        $pdoMock->expects($this->once())->method('query')->with('SELECT * FROM students ORDER BY timestamp DESC')->willReturn($stmtMock);
        $student = new Student($pdoMock);
        $this->assertEquals($expected, $student->getAll());
    }
}
