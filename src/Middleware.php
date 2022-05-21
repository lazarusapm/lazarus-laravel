<?php

namespace Lazarus;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lazarus\Facades\Lazarus;
use WhichBrowser\Parser;

class Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Lazarus::log($this->attributes($request));

        return $next($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function attributes(Request $request): array
    {
        $attributes = [
            'route' => Route::currentRouteName(),
            'url' => $request->url(),
            'method' => $request->method(),
            'timestamp' => now()->toDateTimeString(),
        ];

        if(config('lazarus.ips')) {
            $attributes['ip'] = $request->ip();
        }

        if(config('lazarus.devices')) {
            $result = new Parser(getallheaders());

            $attributes['device'] = [
                'browser' => $result->browser->toString(),
                'engine' => $result->engine->toString(),
                'os' => $result->os->toString(),
                'is_mobile' => $result->isMobile(),
            ];
        }

        return $attributes;
    }
}
