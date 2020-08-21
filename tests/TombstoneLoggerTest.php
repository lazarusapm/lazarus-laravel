<?php

namespace Tombstone\Tests;

use Orchestra\Testbench\TestCase;
use Tombstone\Laravel\TombstoneLogger;
use Tombstone\Laravel\TombstoneService;

class TombstoneLoggerTest extends TestCase
{
    public function test_should_send()
    {
        $params = [
            'route' => 'api.test.index',
            'timestamp' => now()->toDateTimeString(),
            'ip_address' => '192.0.0.1',
        ];

        $service = $this->mock(TombstoneService::class, function ($mock) {
            $mock->shouldReceive('send')
                ->once()
                ->andReturnNull();
        });

        $logger = new TombstoneLogger($service);

        $logger->log($params);
    }
}
