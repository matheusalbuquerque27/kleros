<?php

namespace Modules\Cursos\Providers;

use Illuminate\Support\ServiceProvider;

class CursosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'modules.cursos');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cursos');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('modules/cursos.php'),
        ], 'modules-cursos-config');
    }
}
