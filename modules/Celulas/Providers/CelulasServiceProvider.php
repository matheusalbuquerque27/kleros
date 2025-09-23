<?php

namespace Modules\Celulas\Providers;

use Illuminate\Support\ServiceProvider;

class CelulasServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configPath = __DIR__ . '/../config.php';

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'modules.celulas');
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'celulas');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('modules/celulas.php'),
        ], 'modules-celulas-config');
    }
}
