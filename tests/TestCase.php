<?php

namespace Lazarus\Tests;

use Lazarus\Laravel\Facades\Lazarus;
use Lazarus\Laravel\LazarusServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [LazarusServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Lazarus' => Lazarus::class,
        ];
    }
}
