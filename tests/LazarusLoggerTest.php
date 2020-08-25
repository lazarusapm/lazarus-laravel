<?php

namespace Lazarus\Tests;

use Lazarus\Laravel\LazarusLogger;
use Lazarus\Laravel\LazarusService;

class LazarusLoggerTest extends TestCase
{
    public function test_should_send()
    {
        $params = [
            'route' => 'api.test.index',
            'timestamp' => now()->toDateTimeString(),
            'ip_address' => '192.0.0.1',
        ];

        $service = $this->mock(LazarusService::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->andReturnNull();
        });

        $logger = new LazarusLogger($service);

        $logger->log($params);
    }
}
