<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class ApiTest extends TestCase
{
    public function testRequest(): void
    {
        $client = new Client([
            'base_uri' => 'http://nginx',
        ]);
        $response = $client->get('/index.php');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
