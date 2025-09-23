<?php

namespace App\Providers;

use App\Models\Culto;
use App\Models\Evento;
use App\Models\EncontroCelula;
use App\Models\Reuniao;
use App\Models\Feed;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use App\Models\Extensao;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerEnabledModules();

        Relation::morphMap([
            'culto' => Culto::class,
            'evento' => Evento::class,
            'reuniao' => Reuniao::class,
            'encontro_celula' => EncontroCelula::class,
        ]);

        View::share('appName', config('app.name', 'Kleros'));

        View::composer('*', function ($view) {
            $view->with('congregacao', app()->bound('congregacao') ? app('congregacao') : null);
        });

        app()->singleton('congregacao', function () {
            return Auth::check() ? Auth::user()->congregacao : null;
        });

        app()->singleton('modo_admin', function () {
            return request()->getHost() === 'kleros.local';
        });

        View::composer('noticias.includes.destaques', function ($view) {
            $destaques = Feed::where('fonte', 'guiame')
                ->orderBy('publicado_em', 'desc')->limit(9)->get();
            $view->with('destaques', $destaques);
        });
    }

    protected function registerEnabledModules(): void
    {
        $modulesPath = base_path('modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        $congregacaoId = app()->bound('congregacao') ? optional(app('congregacao'))->id : null;

        $databaseOverrides = collect();

        if (Schema::hasTable('extensoes')) {
            $databaseOverrides = Extensao::query()
                ->when($congregacaoId, fn ($query) => $query->where('congregacao_id', $congregacaoId))
                ->get()
                ->keyBy(fn ($extension) => strtolower($extension->module));
        }

        foreach (File::glob($modulesPath . '/*/module.json') as $manifestPath) {
            $manifest = json_decode(File::get($manifestPath), true) ?: [];
            $moduleKey = strtolower(basename(dirname($manifestPath)));

            $enabled = data_get($manifest, 'enabled', false);

            if ($databaseOverrides->has($moduleKey)) {
                $enabled = $databaseOverrides[$moduleKey]->enabled;
            }

            if (! $enabled) {
                continue;
            }

            $provider = data_get($manifest, 'provider');

            if (! $provider || ! class_exists($provider)) {
                continue;
            }

            $this->app->register($provider);
        }
    }
}
