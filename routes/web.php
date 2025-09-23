<?php

use App\Http\Controllers\CadastroController;
use App\Http\Controllers\CultoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembroController;
use App\Http\Controllers\MinisterioController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\CongregacaoController;
use App\Http\Controllers\DenominacaoController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\LivrariaController;
use App\Http\Controllers\ReuniaoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\LocalizacaoController;
use App\Http\Controllers\ExtensoesController;

Route::domain('kleros.local')->group(function () {
    
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // outras rotas administrativas...
    });

    Route::get('/denominacoes', [DenominacaoController::class, 'index'])->name('denominacoes.index');
    Route::post('/denominacoes', [DenominacaoController::class, 'store'])->name('denominacoes.store');
    Route::get('/denominacoes/{id}', [DenominacaoController::class, 'show'])->name('denominacoes.show');
    Route::delete('/denominacoes/{id}', [DenominacaoController::class, 'destroy'])->name('denominacoes.destroy');
    
    //Rotas de cadastro
    Route::get('/congregacoes', [CongregacaoController::class, 'index'])->name('congregacoes.index');
    Route::get('/checkin/denominacao', [DenominacaoController::class, 'create'])->name('denominacoes.create');
    Route::get('/checkin', [CongregacaoController::class, 'create'])->name('congregacoes.cadastro');
    Route::get('/config/{id}', [CongregacaoController::class, 'config'])->name('congregacoes.config');

});

Route::middleware(['web', 'dominio'])->group(function () {
    Route::get('/login', [HomeController::class, 'login'])->name('login');
    Route::get('/cadastrar', [HomeController::class, 'create'])->name('login.create');
    Route::post('/cadastrar', [HomeController::class, 'store'])->name('login.store');
    Route::get('/recuperar-senha', [HomeController::class, 'forgotPassword'])->name('login.forgot');
    Route::post('/recuperar-senha', [HomeController::class, 'sendResetLink'])->name('login.sendResetLink');
    Route::get('/recuperar-senha/{token}', [HomeController::class, 'resetPassword'])->name('login.reset');
    Route::post('/recuperar-senha/{token}', [HomeController::class, 'updatePassword'])->name('login.updatePassword');
    Route::post('/login', [HomeController::class, 'authenticate']);
    Route::get('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');

    //Rotas para já cadastradas
    Route::get('/configuracoes/{id}', [CongregacaoController::class, 'editar'])->name('configuracoes.editar');
    Route::put('/configuracoes/{id}', [CongregacaoController::class, 'update'])->name('configuracoes.atualizar');
    Route::delete('/configuracoes/{id}', [CongregacaoController::class, 'destroy'])->name('configuracoes.excluir');

    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/cadastros', [CadastroController::class, 'index'])->name('cadastros.index');
    
    Route::get('/tutoriais', [TutorialController::class, 'index'])->name('tutoriais.index');

    Route::post('/membros', [MembroController::class, 'store'])->name('membros.store');
    Route::get('/membros/adicionar', [MembroController::class, 'adicionar'])->name('membros.adicionar'); 
    Route::get('/membros/painel', [MembroController::class, 'painel'])->name('membros.painel');
    Route::post('/membros/search', [MembroController::class, 'search'])->name('membros.search');
    Route::get('/membros/exibir/{id}', [MembroController::class, 'show']);
    Route::get('/membros/editar/{id}', [MembroController::class, 'editar'])->name('membros.editar');
    Route::put('/membros/{id}', [MembroController::class, 'update'])->name('membros.atualizar');
    Route::delete('/membros/{id}', [MembroController::class, 'destroy'])->name('membros.destroy');

    Route::get('/perfil', [MembroController::class, 'perfil'])->name('perfil');
    Route::put('/perfil/{id}', [MembroController::class, 'save_perfil'])->name('perfil.update');

    Route::post('/visitantes', [VisitanteController::class, 'store']);
    Route::get('/visitantes/adicionar', [VisitanteController::class, 'create'])->name('visitantes.adicionar');
    Route::get('/visitantes/historico', [VisitanteController::class, 'historico'])->name('visitantes.historico');
    Route::post('/visitantes/search', [VisitanteController::class, 'search']);
    Route::get('/visitantes/{id}', [VisitanteController::class, 'exibir'])->name('visitantes.exibir');
    Route::get('/visitantes/editar/{id}', [VisitanteController::class, 'form_editar'])->name('visitantes.form_editar');
    Route::put('/visitantes/{id}', [VisitanteController::class, 'update'])->name('visitantes.update');
    Route::delete('/visitantes/{id}', [VisitanteController::class, 'destroy'])->name('visitantes.destroy');
    
    Route::post('/grupos', [GrupoController::class, 'store']);
    Route::delete('/grupos/{id}', [GrupoController::class, 'destroy']);
    Route::get('/grupos/integrantes/{id}', [GrupoController::class, 'show'])->name('grupos.integrantes');
    Route::post('/grupos/integrantes', [GrupoController::class, 'addMember']);
    Route::get('/grupos/imprimir/{data}', [GrupoController::class, 'print']);
    Route::get('/grupos/novo', [GrupoController::class, 'form_criar'])->name('grupos.form_criar');
    Route::get('/grupos/editar/{id}', [GrupoController::class, 'form_editar'])->name('grupos.form_editar');
    Route::put('/grupos/{id}', [GrupoController::class, 'update'])->name('grupos.update');
    Route::get('/grupos/lista', [GrupoController::class, 'lista'])->name('grupos.lista');
    
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/adicionar', [EventoController::class, 'create'])->name('eventos.create');
    Route::get('/eventos/historico', [EventoController::class, 'index'])->name('eventos.historico');
    Route::post('/eventos/search', [EventoController::class, 'search'])->name('eventos.search');
    Route::get('/eventos/agenda', [EventoController::class, 'agenda'])->name('eventos.agenda');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::get('/eventos/novo', [EventoController::class, 'form_criar'])->name('eventos.form_criar');
    Route::get('/eventos/editar/{id}', [EventoController::class, 'form_editar'])->name('eventos.form_editar');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');

    
    Route::post('/cultos', [CultoController::class, 'store'])->name('cultos.store');
    Route::get('/cultos/agenda', [CultoController::class, 'agenda'])->name('cultos.agenda');
    Route::get('/cultos/historico', [CultoController::class, 'index'])->name('cultos.historico');
    Route::post('cultos/search', [CultoController::class, 'search'])->name('cultos.search');
    Route::get('/cultos/agendamento', [CultoController::class, 'create'])->name('cultos.create');
    Route::get('/cultos/novo', [CultoController::class, 'form_criar'])->name('cultos.form_criar');
    Route::get('/cultos/{id}', [CultoController::class, 'complete'])->name('cultos.complete');
    Route::get('/cultos/editar/{id}', [CultoController::class, 'form_editar'])->name('cultos.form_editar');
    Route::put('/cultos/{id}', [CultoController::class, 'update'])->name('cultos.update');
    Route::delete('/cultos/{id}', [CultoController::class, 'destroy'])->name('cultos.destroy');
    
    Route::post('/ministerios', [MinisterioController::class, 'store'])->name('ministerios.store');
    Route::get('/ministerios/novo', [MinisterioController::class, 'form_criar'])->name('ministerios.form_criar');
    Route::get('/ministerios/editar/{id}', [MinisterioController::class, 'form_editar'])->name('ministerios.form_editar');
    Route::get('/ministerios/lista/{id}', [MinisterioController::class, 'lista'])->name('ministerios.lista');
    Route::delete('/ministerios/{id}', [MinisterioController::class, 'destroy'])->name('ministerios.destroy');
    Route::get('/ministerios/imprimir/{data}', [MinisterioController::class, 'print'])->name('ministerios.print');
    Route::put('/ministerios/{id}', [MinisterioController::class, 'update'])->name('ministerios.update');
    
    Route::get('/departamentos', [DepartamentoController::class, 'painel'])->name('departamentos.painel');
    Route::get('/departamentos/adicionar', [DepartamentoController::class, 'create'])->name('departamentos.create');
    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::put('/departamentos/{id}', [DepartamentoController::class, 'update'])->name('departamentos.update');
    Route::get('/departamentos/novo', [DepartamentoController::class, 'form_criar'])->name('departamentos.form_criar');
    Route::get('/departamentos/editar/{id}', [DepartamentoController::class, 'form_editar'])->name('departamentos.form_editar');
    Route::delete('/departamentos/{id}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
    
    Route::get('/setores', [CongregacaoController::class, 'index'])->name('setores.index');
    Route::get('/setores/adicionar', [CongregacaoController::class, 'create'])->name('setores.create');
    Route::post('/setores', [CongregacaoController::class, 'store'])->name('setores.store');
    Route::get('/setores/{id}', [CongregacaoController::class, 'show'])->name('setores.show');
    Route::put('/setores/{id}', [CongregacaoController::class, 'update'])->name('setores.update');
    Route::delete('/setores/{id}', [CongregacaoController::class, 'destroy'])->name('setores.destroy');

    Route::put('/denominacoes/{id}', [DenominacaoController::class, 'update'])->name('denominacoes.update');
    
    Route::get('/noticias', [FeedController::class, 'noticias'])->name('noticias.painel');
    Route::get('/destaques', [FeedController::class, 'destaques'])->name('noticias.destaques');
    Route::get('/podcasts', [FeedController::class, 'podcasts'])->name('podcasts.painel');
    Route::get('/feeds', [FeedController::class, 'index'])->name('feeds.index');
    Route::get('/feeds/{slug}', [FeedController::class, 'show'])->name('feeds.show');

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/eventos', [AgendaController::class, 'eventosJson'])->name('agenda.eventos.json');

    Route::get('/livraria', [LivrariaController::class, 'index'])->name('livraria.index');
    Route::post('/livraria/search', [LivrariaController::class, 'search'])->name('livraria.search');

    Route::get('/reunioes', [ReuniaoController::class, 'create'])->name('reunioes.create');
    Route::get('/reunioes/painel', [ReuniaoController::class, 'index'])->name('reunioes.painel');
    Route::post('/reunioes', [ReuniaoController::class, 'store'])->name('reunioes.store');
    Route::get('/reunioes/novo', [ReuniaoController::class, 'form_criar'])->name('reunioes.form_criar');
    Route::get('/reunioes/editar/{id}', [ReuniaoController::class, 'form_editar'])->name('reunioes.form_editar');
    Route::put('/reunioes/{id}', [ReuniaoController::class, 'update'])->name('reunioes.update');

    Route::get('/avisos/admin', [AvisoController::class, 'index'])->name('avisos.admin');
    Route::get('/avisos', [AvisoController::class, 'avisosDoMembro'])->name('avisos.painel');
    Route::post('/avisos', [AvisoController::class, 'store'])->name('avisos.store');
    Route::get('/avisos/novo', [AvisoController::class, 'form_criar'])->name('avisos.form_criar');

    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/adicionar', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/{id}', [CursoController::class, 'show'])->name('cursos.show');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::get('/arquivos/imagens', [ArquivoController::class, 'form_imagens'])->name('arquivos.imagens');
    Route::post('/arquivos', [ArquivoController::class, 'store'])->name('arquivos.store');
    Route::delete('/arquivos/{id}', [ArquivoController::class, 'destroy'])->name('arquivos.destroy');
    Route::get('/arquivos/lista_imagens', [ArquivoController::class, 'lista_imagens'])->name('arquivos.lista_imagens');

    Route::get('/relatorios', [RelatorioController::class, 'painel'])->name('relatorios.painel');

    //Rotas para buscas dinâmicas de localização
    Route::get('/estados/{pais_id}', [LocalizacaoController::class, 'getEstados'])->name('localizacao.estados');
    Route::get('/cidades/{uf}', [LocalizacaoController::class, 'getCidades'])->name('localizacao.cidades');

    Route::get('/extensoes', [ExtensoesController::class, 'index'])->name('extensoes.painel');
    Route::put('/extensoes/{module}', [ExtensoesController::class, 'update'])->name('extensoes.update');
});
