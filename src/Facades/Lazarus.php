<?php

namespace Lazarus\Facades;

use Illuminate\Support\Facades\Facade;

class Lazarus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lazarus';
    }
}
