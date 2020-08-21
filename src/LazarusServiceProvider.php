<?php

namespace Lazarus\Laravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Lazarus\Laravel\Facades\Lazarus;

class LazarusServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__.'/../config/lazarus.php';

        $this->publishes([
            $configPath => $this->configPath('lazarus.php'),
        ], 'lazarus');

        $this->mergeConfigFrom($configPath, 'lazarus');

        $this->registerMiddleware();
    }

    public function register()
    {
        $this->app->alias('Lazarus', Lazarus::class);

        $this->app->singleton(LazarusLogger::class, function () {
            return new LazarusLogger(
                new LazarusService(config('lazarus'))
            );
        });

        $this->app->bind('lazarus', LazarusLogger::class);
    }

    public function provides(): array
    {
        return ['lazarus', LazarusLogger::class];
    }

    protected function registerMiddleware()
    {
        $this->app[Kernel::class]->appendMiddlewareToGroup('api', LogRoute::class);
    }

    protected function configPath(string $path = ''): string
    {
        if (function_exists('config_path')) {
            return config_path($path);
        }

        $path_parts = [
            app()->basePath(),
            'config',
            trim($path, '/'),
        ];

        return implode('/', $path_parts);
    }
}
