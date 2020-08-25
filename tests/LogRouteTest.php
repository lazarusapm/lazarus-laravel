<?php

namespace Lazarus\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Lazarus\Laravel\Facades\Lazarus;

class LogRouteTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('lazarus.token', Str::random(60));
    }

    public function test_log_payload()
    {
        Carbon::setTestNow('2020-10-01 13:00:00');

        Lazarus::shouldReceive('log')
            ->once()
            ->with([
                'route' => 'api.test.index',
                'url' => 'http://localhost/test',
                'ip_address' => '135.53.70.123',
                'timestamp' => '2020-10-01 13:00:00',
            ])
            ->andReturnNull();

        Route::get('/test', function () {
            return 'Test';
        })->middleware('api')->name('api.test.index');

        $this->getJson('/test', [
            'REMOTE_ADDR' => '135.53.70.123',
        ])
            ->assertSuccessful();
    }
}
