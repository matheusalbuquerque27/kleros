<?php

use Illuminate\Support\Facades\Route;
use Modules\Biblia\Http\Controllers\BibliaController;

Route::middleware(['web', 'dominio'])->group(function () {
    Route::prefix('biblia')->group(function () {
        Route::get('/', [BibliaController::class, 'index'])->name('biblia.index');
        Route::get('/livro/{bookId}', [BibliaController::class, 'chapters'])->name('biblia.chapters');
        Route::get('/livro/{bookId}/capitulo/{chapter}', [BibliaController::class, 'verses'])->name('biblia.verses');
        Route::get('/buscar', [BibliaController::class, 'search'])->name('biblia.search');
    });
});
