<?php

namespace Lazarus\Tests;

use Lazarus\Facades\Lazarus;
use Lazarus\LazarusServiceProvider;

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
