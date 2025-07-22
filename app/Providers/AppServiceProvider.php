<?php

namespace App\Providers;

use App\Models\Culto;
use App\Models\Evento;
use App\Models\EncontroCelula;
use App\Models\Reuniao;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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

        app()->singleton('modo_admin', function () {
            return request()->getHost() === 'kleros.local';
        });
    }
}
