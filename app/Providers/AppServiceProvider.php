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
}
