<?php

use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testDbHostFromEnv(): void
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->assertSame('db', $host);
    }
}
