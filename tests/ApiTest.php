<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('http')]
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
