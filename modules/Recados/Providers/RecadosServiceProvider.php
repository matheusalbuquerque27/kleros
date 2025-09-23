<?php

namespace Modules\Recados\Providers;

use Illuminate\Support\ServiceProvider;

class RecadosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'modules.recados');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'recados');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('modules/recados.php'),
        ], 'modules-recados-config');
    }
}
