<?php

use App\Http\Controllers\CadastroController;
use App\Http\Controllers\CultoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembroController;
use App\Http\Controllers\MinisterioController;
use App\Http\Controllers\RecadoController;
use App\Http\Controllers\VisitanteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::post('/membros', [MembroController::class, 'store']);
Route::get('/membros/adicionar', [MembroController::class, 'adicionar'])->name('membros.adicionar'); 
Route::get('/membros/painel', [MembroController::class, 'painel'])->name('membros.painel');
Route::post('/membros/search', [MembroController::class, 'search']);
Route::get('/membros/exibir/{id}', [MembroController::class, 'show']);
Route::get('/membros/editar/{id}', [MembroController::class, 'editar']);
Route::put('/membros/{id}', [MembroController::class, 'update'])->name('membros.atualizar');
Route::delete('/membros/{id}', [MembroController::class, 'destroy'])->name('membros.excluir');

Route::post('/visitantes', [VisitanteController::class, 'store']);
Route::get('/visitantes/adicionar', [VisitanteController::class, 'create'])->name('visitantes.adicionar');
Route::get('/visitantes/historico', [VisitanteController::class, 'historico']);
Route::post('/visitantes/search', [VisitanteController::class, 'search']);

Route::post('/grupos', [GrupoController::class, 'store']);
Route::get('/grupos/adicionar', [GrupoController::class, 'create']);
Route::delete('/grupos/{id}', [GrupoController::class, 'destroy']);
Route::get('/grupos/integrantes/{id}', [GrupoController::class, 'show'])->name('grupos.integrantes');
Route::post('/grupos/integrantes', [GrupoController::class, 'addMember']);
Route::get('/grupos/imprimir/{data}', [GrupoController::class, 'print']);

Route::post('/eventos', [EventoController::class, 'store']);
Route::get('/eventos/adicionar', [EventoController::class, 'create']);
Route::get('/eventos/historico', [EventoController::class, 'index']);
Route::post('/eventos/search', [EventoController::class, 'search']);
Route::get('/eventos/agenda', [EventoController::class, 'agenda']);

Route::post('/recados', [RecadoController::class, 'store']);
Route::get('/recados/adicionar', [RecadoController::class, 'create']);
Route::delete('/recados/{id}', [RecadoController::class, 'destroy'])->name('recados.excluir');

Route::post('/cultos', [CultoController::class, 'store']);
Route::get('/cultos/agenda', [CultoController::class, 'agenda']);
Route::get('/cultos/historico', [CultoController::class, 'index']);
Route::post('cultos/search', [CultoController::class, 'search']);
Route::get('/cultos/cultos', [CultoController::class, 'create']);

Route::post('/ministerios', [MinisterioController::class, 'store']);
Route::get('/ministerios/adicionar', [MinisterioController::class, 'create']);
Route::get('/ministerios/lista/{id}', [MinisterioController::class, 'lista']);
Route::delete('/ministerios/{id}', [MinisterioController::class, 'destroy']);
Route::get('/ministerios/imprimir/{data}', [MinisterioController::class, 'print']);

Route::get('/cadastros', [CadastroController::class, 'index']);