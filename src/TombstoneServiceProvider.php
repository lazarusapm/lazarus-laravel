<?php

namespace Tombstone\Laravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Tombstone\Laravel\Facades\Tombstone;

class TombstoneServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__.'/../config/tombstone.php';

        $this->publishes([
            $configPath => $this->configPath('tombstone.php'),
        ], 'tombstone');

        $this->mergeConfigFrom($configPath, 'tombstone');

        $this->registerMiddleware();
    }

    public function register()
    {
        $this->app->alias('Tombstone', Tombstone::class);

        $this->app->singleton(TombstoneLogger::class, function () {
            return new TombstoneLogger(
                new TombstoneService(config('tombstone'))
            );
        });

        $this->app->bind('tombstone', TombstoneLogger::class);
    }

    public function provides(): array
    {
        return ['tombstone', TombstoneLogger::class];
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
