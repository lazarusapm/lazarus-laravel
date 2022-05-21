<?php

namespace Lazarus;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Lazarus\Commands\CreateMiddleware;
use Lazarus\Commands\TransferLazarusFile;
use Lazarus\Facades\Lazarus;

class LazarusServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function boot()
    {
        $this->configure();
        $this->registerCommands();
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return ['lazarus', LazarusLogger::class];
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $configPath = __DIR__.'/../config/lazarus.php';

        $this->publishes([
            $configPath => $this->configPath('lazarus.php'),
        ], 'lazarus');

        $this->mergeConfigFrom($configPath, 'lazarus');
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateMiddleware::class,
                TransferLazarusFile::class,
            ]);
        }
    }

    /**
     * @param string $path
     * @return string
     */
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
