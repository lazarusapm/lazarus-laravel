<?php

namespace Lazarus\Laravel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lazarus\Laravel\Facades\Lazarus;

class LogRoute
{
    public function handle(Request $request, Closure $next)
    {
        Lazarus::log([
            'route' => Route::currentRouteName(),
            'ip_address' => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
