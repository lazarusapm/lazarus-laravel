<?php

namespace Tombstone\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Tombstone extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tombstone';
    }
}
