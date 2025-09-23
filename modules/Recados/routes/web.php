<?php

use Illuminate\Support\Facades\Route;
use Modules\Recados\Http\Controllers\RecadoController;

Route::middleware(['web', 'dominio'])->group(function () {
    Route::get('/recados', [RecadoController::class, 'historico'])->name('recados.historico');
    Route::post('/recados', [RecadoController::class, 'store'])->name('recados.store');
    Route::get('/recados/adicionar', [RecadoController::class, 'create'])->name('recados.create');
    Route::get('/recados/listar/{id}', [RecadoController::class, 'list'])->name('recados.listar');
    Route::delete('/recados/{id}', [RecadoController::class, 'destroy'])->name('recados.excluir');
});
