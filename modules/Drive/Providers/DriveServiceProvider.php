<?php

namespace Modules\Drive\Providers;

use Illuminate\Support\ServiceProvider;

class DriveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'modules.drive');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'drive');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('modules/drive.php'),
        ], 'modules-drive-config');
    }
}
