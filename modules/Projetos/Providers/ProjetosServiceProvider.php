<?php

namespace Modules\Projetos\Providers;

use Illuminate\Support\ServiceProvider;

class ProjetosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'modules.projetos');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'projetos');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('modules/projetos.php'),
        ], 'modules-projetos-config');
    }
}
