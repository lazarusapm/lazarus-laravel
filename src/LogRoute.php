<?php

namespace Tombstone\Laravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tombstone\Laravel\Facades\Tombstone;

class LogRoute
{
    public function handle(Request $request, Closure $next)
    {
        Tombstone::log([
            'route' => Route::currentRouteName(),
            'ip_address' => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
