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
use App\Http\Controllers\CongregacaoController;
use App\Http\Controllers\IgrejaController;
use App\Http\Controllers\CelulaController;
use App\Http\Controllers\DenominacaoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/cadastrar', [HomeController::class, 'create'])->name('login.create');
Route::post('/cadastrar', [HomeController::class, 'store'])->name('login.store');
Route::get('/recuperar-senha', [HomeController::class, 'forgotPassword'])->name('login.forgot');
Route::post('/recuperar-senha', [HomeController::class, 'sendResetLink'])->name('login.sendResetLink');
Route::get('/recuperar-senha/{token}', [HomeController::class, 'resetPassword'])->name('login.reset');
Route::post('/recuperar-senha/{token}', [HomeController::class, 'updatePassword'])->name('login.updatePassword');
Route::post('/login', [HomeController::class, 'authenticate']);
Route::get('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/cadastros', [CadastroController::class, 'index'])->name('cadastros.index');

Route::post('/membros', [MembroController::class, 'store'])->name('membros.store');
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
Route::get('/visitantes/{id}', [VisitanteController::class, 'show'])->name('visitantes.show');
Route::delete('/visitantes/{id}', [VisitanteController::class, 'destroy'])->name('visitantes.destroy');
Route::put('/visitantes/{id}', [VisitanteController::class, 'update'])->name('visitantes.update');

Route::post('/grupos', [GrupoController::class, 'store']);
Route::get('/grupos/adicionar', [GrupoController::class, 'create']);
Route::delete('/grupos/{id}', [GrupoController::class, 'destroy']);
Route::get('/grupos/integrantes/{id}', [GrupoController::class, 'show'])->name('grupos.integrantes');
Route::post('/grupos/integrantes', [GrupoController::class, 'addMember']);
Route::get('/grupos/imprimir/{data}', [GrupoController::class, 'print']);
Route::put('/grupos/{id}', [GrupoController::class, 'update'])->name('grupos.update');
Route::get('/grupos/lista', [GrupoController::class, 'lista'])->name('grupos.lista');

Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::get('/eventos/adicionar', [EventoController::class, 'create'])->name('eventos.create');
Route::get('/eventos/historico', [EventoController::class, 'index'])->name('eventos.historico');
Route::post('/eventos/search', [EventoController::class, 'search'])->name('eventos.search');
Route::get('/eventos/agenda', [EventoController::class, 'agenda'])->name('eventos.agenda');
Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');

Route::get('/recados', [RecadoController::class, 'index'])->name('recados.index');
Route::post('/recados', [RecadoController::class, 'store'])->name('recados.store');
Route::get('/recados/adicionar', [RecadoController::class, 'create'])->name('recados.create');
Route::delete('/recados/{id}', [RecadoController::class, 'destroy'])->name('recados.excluir');

Route::post('/cultos', [CultoController::class, 'store'])->name('cultos.store');
Route::get('/cultos/agenda', [CultoController::class, 'agenda'])->name('cultos.agenda');
Route::get('/cultos/historico', [CultoController::class, 'index'])->name('cultos.historico');
Route::post('cultos/search', [CultoController::class, 'search'])->name('cultos.search');
Route::get('/cultos/cultos', [CultoController::class, 'create'])->name('cultos.create');
Route::get('/cultos/{id}', [CultoController::class, 'show'])->name('cultos.show');
Route::put('/cultos/{id}', [CultoController::class, 'update'])->name('cultos.update');
Route::delete('/cultos/{id}', [CultoController::class, 'destroy'])->name('cultos.destroy');

Route::post('/ministerios', [MinisterioController::class, 'store'])->name('ministerios.store');
Route::get('/ministerios/adicionar', [MinisterioController::class, 'create'])->name('ministerios.create');
Route::get('/ministerios/lista/{id}', [MinisterioController::class, 'lista'])->name('ministerios.lista');
Route::delete('/ministerios/{id}', [MinisterioController::class, 'destroy'])->name('ministerios.destroy');
Route::get('/ministerios/imprimir/{data}', [MinisterioController::class, 'print'])->name('ministerios.print');
Route::put('/ministerios/{id}', [MinisterioController::class, 'update'])->name('ministerios.update');

Route::get('/celulas', [CelulaController::class, 'index'])->name('celulas.index');
Route::get('/celulas/adicionar', [CelulaController::class, 'create'])->name('celulas.create');
Route::post('/celulas', [CelulaController::class, 'store'])->name('celulas.store');
Route::get('/celulas/{id}', [CelulaController::class, 'show'])->name('celulas.show');
Route::put('/celulas/{id}', [CelulaController::class, 'update'])->name('celulas.update');
Route::delete('/celulas/{id}', [CelulaController::class, 'destroy'])->name('celulas.destroy');

Route::get('/departamentos', [CongregacaoController::class, 'index'])->name('departamentos.index');
Route::get('/departamentos/adicionar', [CongregacaoController::class, 'create'])->name('departamentos.create');
Route::post('/departamentos', [CongregacaoController::class, 'store'])->name('departamentos.store');
Route::get('/departamentos/{id}', [CongregacaoController::class, 'show'])->name('departamentos.show');
Route::put('/departamentos/{id}', [CongregacaoController::class, 'update'])->name('departamentos.update');
Route::delete('/departamentos/{id}', [CongregacaoController::class, 'destroy'])->name('departamentos.destroy');

Route::get('/setores', [CongregacaoController::class, 'index'])->name('setores.index');
Route::get('/setores/adicionar', [CongregacaoController::class, 'create'])->name('setores.create');
Route::post('/setores', [CongregacaoController::class, 'store'])->name('setores.store');
Route::get('/setores/{id}', [CongregacaoController::class, 'show'])->name('setores.show');
Route::put('/setores/{id}', [CongregacaoController::class, 'update'])->name('setores.update');
Route::delete('/setores/{id}', [CongregacaoController::class, 'destroy'])->name('setores.destroy');

Route::get('/igrejas', [DenominacaoController::class, 'index'])->name('denominacoes.index');
Route::post('/igrejas', [DenominacaoController::class, 'store'])->name('denominacoes.store');
Route::get('/igrejas/{id}', [DenominacaoController::class, 'show'])->name('denominacoes.show');
Route::put('/igrejas/{id}', [DenominacaoController::class, 'update'])->name('denominacoes.update');
Route::delete('/igrejas/{id}', [DenominacaoController::class, 'destroy'])->name('denominacoes.destroy');

Route::get('/checkin/igreja', [DenominacaoController::class, 'create'])->name('denominacoes.create');
Route::get('/checkin', [CongregacaoController::class, 'create'])->name('congregacoes.cadastro');
Route::get('/config/{id}', [CongregacaoController::class, 'config'])->name('congregacoes.config');