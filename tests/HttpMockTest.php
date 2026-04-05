<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class HttpMockTest extends TestCase
{
    public function testMockRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'OK'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->get('/test');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
