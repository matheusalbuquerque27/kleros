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
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EscalaController;
use App\Http\Controllers\LocalizacaoController;
use App\Http\Controllers\ExtensoesController;
use App\Http\Controllers\PesquisaController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\LocaleController;

Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::domain('kleros.local')
    ->middleware('setlocale')
    ->group(function () {
    Route::get('/', [SiteController::class, 'home'])->name('site.home');
    Route::view('/contato', 'site.contato')->name('site.contato');
    Route::view('/demo', 'site.demo')->name('site.demo');

    Route::get('/denominacoes', [DenominacaoController::class, 'index'])->name('denominacoes.index');
    Route::post('/denominacoes', [DenominacaoController::class, 'store'])->name('denominacoes.store');
    Route::get('/denominacoes/{id}', [DenominacaoController::class, 'show'])->name('denominacoes.show');
    Route::delete('/denominacoes/{id}', [DenominacaoController::class, 'destroy'])->name('denominacoes.destroy');
    
    //Rotas de cadastro
    Route::get('/congregacoes', [CongregacaoController::class, 'index'])->name('congregacoes.index');
    Route::post('/congregacoes', [CongregacaoController::class, 'store'])->name('congregacoes.store');
    Route::get('/checkin/denominacao', [DenominacaoController::class, 'create'])->name('denominacoes.create');
    Route::get('/checkin', [CongregacaoController::class, 'create'])->name('congregacoes.cadastro');
    Route::get('/configuracoes/{congregacao}', [CongregacaoController::class, 'config'])->name('congregacoes.config');
    Route::post('/configuracoes/{congregacao}', [CongregacaoController::class, 'salvarConfig'])->name('congregacoes.config.salvar');
});

Route::domain('admin.local')->middleware('setlocale')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.login'));
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // outras rotas administrativas...
    });

});

Route::middleware(['web', 'dominio', 'setlocale'])->group(function () {

    Route::get('/login', [HomeController::class, 'login'])->name('login');
    Route::get('/cadastrar', [HomeController::class, 'create'])->name('login.create');
    Route::post('/cadastrar', [HomeController::class, 'store'])->name('login.store');
    Route::get('/recuperar-senha', [HomeController::class, 'forgotPassword'])->name('login.forgot');
    Route::post('/recuperar-senha', [HomeController::class, 'sendResetLink'])->name('login.sendResetLink');
    Route::get('/recuperar-senha/{token}', [HomeController::class, 'resetPassword'])->name('login.reset');
    Route::post('/recuperar-senha/{token}', [HomeController::class, 'updatePassword'])->name('login.updatePassword');
    Route::post('/login', [HomeController::class, 'authenticate']);
    Route::get('/logout', function () {Auth::logout();return redirect()->route('login');})->name('logout');

    Route::middleware(['auth','check.session','member.activity'])->group(function () {

        //Rotas para já cadastradas
        Route::get('/configuracoes/{id}', [CongregacaoController::class, 'editar'])->name('configuracoes.editar')->middleware(['auth','role:gestor']);
        Route::put('/configuracoes/{id}', [CongregacaoController::class, 'update'])->name('configuracoes.atualizar')->middleware(['auth','role:gestor']);
        Route::delete('/configuracoes/{id}', [CongregacaoController::class, 'destroy'])->name('configuracoes.excluir')->middleware(['auth','role:gestor']);

        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::get('/cadastros', [CadastroController::class, 'index'])->name('cadastros.index')->middleware(['auth','role:gestor']);
        
        Route::get('/tutoriais', [TutorialController::class, 'index'])->name('tutoriais.index')->middleware(['auth','role:gestor']);

        Route::get('/perfil', [MembroController::class, 'perfil'])->name('perfil')->middleware('auth');
        Route::put('/perfil/{id}', [MembroController::class, 'save_perfil'])->name('perfil.update')->middleware('auth');

        Route::middleware(['auth','role:gestor'])->group(function () {
        Route::post('/membros', [MembroController::class, 'store'])->name('membros.store');
        Route::get('/membros/adicionar', [MembroController::class, 'adicionar'])->name('membros.adicionar'); 
        Route::get('/membros/painel', [MembroController::class, 'painel'])->name('membros.painel');
        Route::get('/membros/export', [MembroController::class, 'export'])->name('membros.export');
        Route::get('/membros/{id}', [MembroController::class, 'editar'])->name('membros.editar');
        Route::post('/membros/search', [MembroController::class, 'search'])->name('membros.search');
        Route::get('/membros/exibir/{id}', [MembroController::class, 'show']);
        Route::get('/membros/editar/{id}', [MembroController::class, 'form_editar'])->name('membros.form_editar');
        Route::put('/membros/{id}', [MembroController::class, 'update'])->name('membros.atualizar');
        Route::delete('/membros/{id}', [MembroController::class, 'destroy'])->name('membros.destroy');

        Route::post('/visitantes', [VisitanteController::class, 'store'])->name('visitantes.store');
        Route::get('/visitantes/adicionar', [VisitanteController::class, 'create'])->name('visitantes.adicionar');
        Route::get('/visitantes/historico', [VisitanteController::class, 'historico'])->name('visitantes.historico');
        Route::get('/visitantes/export', [VisitanteController::class, 'export'])->name('visitantes.export');
        Route::post('/visitantes/search', [VisitanteController::class, 'search'])->name('visitantes.search');
        Route::get('/visitantes/{id}', [VisitanteController::class, 'exibir'])->name('visitantes.exibir');
        Route::get('/visitantes/editar/{id}', [VisitanteController::class, 'form_editar'])->name('visitantes.form_editar');
        Route::put('/visitantes/{id}', [VisitanteController::class, 'update'])->name('visitantes.update');
        Route::post('/visitantes/membrar', [VisitanteController::class, 'tornarMembro'])->name('visitantes.membrar');
        Route::delete('/visitantes/{id}', [VisitanteController::class, 'destroy'])->name('visitantes.destroy');
        
        Route::post('/grupos', [GrupoController::class, 'store']);
        Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
        Route::get('/grupos/integrantes/{id}', [GrupoController::class, 'show'])->name('grupos.integrantes');
        Route::post('/grupos/integrantes', [GrupoController::class, 'addMember']);
        Route::delete('/grupos/integrantes/{grupo}/{membro}', [GrupoController::class, 'removeMember'])->name('grupos.integrantes.remover');
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
        Route::delete('/ministerios/{ministerio}/membros/{membro}', [MinisterioController::class, 'remover'])->name('ministerios.membros.remover');
        Route::get('/ministerios/imprimir/{data}', [MinisterioController::class, 'print'])->name('ministerios.print');
        Route::put('/ministerios/{id}', [MinisterioController::class, 'update'])->name('ministerios.update');
        Route::put('/ministerios/incluir/{ministerio}', [MinisterioController::class, 'incluir']);
        
        Route::get('/departamentos', [DepartamentoController::class, 'painel'])->name('departamentos.painel');
        Route::get('/departamentos/adicionar', [DepartamentoController::class, 'create'])->name('departamentos.create');
        Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
        Route::post('/departamentos/search', [DepartamentoController::class, 'search'])->name('departamentos.search');
        Route::put('/departamentos/{id}', [DepartamentoController::class, 'update'])->name('departamentos.update');
        Route::get('/departamentos/novo', [DepartamentoController::class, 'form_criar'])->name('departamentos.form_criar');
        Route::get('/departamentos/editar/{id}', [DepartamentoController::class, 'form_editar'])->name('departamentos.form_editar');
        Route::delete('/departamentos/{id}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');

        Route::get('/escalas/novo/{culto?}', [EscalaController::class, 'form_criar'])->name('escalas.form_criar');
        Route::post('/escalas', [EscalaController::class, 'store'])->name('escalas.store');
        Route::get('/escalas/editar/{id}', [EscalaController::class, 'form_editar'])->name('escalas.form_editar');
        Route::get('/escalas/painel', [EscalaController::class, 'painel'])->name('escalas.painel');
        Route::post('/escalas/search', [EscalaController::class, 'search'])->name('escalas.search');
        Route::get('/escalas/tipos/novo', [EscalaController::class, 'form_tipo_criar'])->name('escalas.tipos.form_criar');
        Route::post('/escalas/tipos', [EscalaController::class, 'store_tipo'])->name('escalas.tipos.store');
        Route::get('/escalas/tipos/editar/{id}', [EscalaController::class, 'form_tipo_editar'])->name('escalas.tipos.form_editar');
        Route::put('/escalas/tipos/{id}', [EscalaController::class, 'update_tipo'])->name('escalas.tipos.update');
        Route::delete('/escalas/tipos/{id}', [EscalaController::class, 'destroy_tipo'])->name('escalas.tipos.destroy');
        Route::put('/escalas/{id}', [EscalaController::class, 'update'])->name('escalas.update');
        Route::delete('/escalas/{id}', [EscalaController::class, 'destroy'])->name('escalas.destroy');

        Route::post('/setores', [SetorController::class, 'store'])->name('setores.store');
        Route::get('/setores/novo', [SetorController::class, 'form_criar'])->name('setores.form_criar');
        Route::get('/setores/editar/{id}', [SetorController::class, 'form_editar'])->name('setores.form_editar');
        Route::put('/setores/{id}', [SetorController::class, 'update'])->name('setores.update');
        Route::delete('/setores/{id}', [SetorController::class, 'destroy'])->name('setores.destroy');

        Route::put('/denominacoes/{id}', [DenominacaoController::class, 'update'])->name('denominacoes.update');
        });
        
        Route::get('/noticias', [FeedController::class, 'noticias'])->name('noticias.painel')->middleware('auth');
        Route::get('/destaques', [FeedController::class, 'destaques'])->name('noticias.destaques')->middleware('auth');
        Route::get('/podcasts', [FeedController::class, 'podcasts'])->name('podcasts.painel')->middleware('auth');
        Route::get('/feeds', [FeedController::class, 'index'])->name('feeds.index')->middleware('auth');
        Route::get('/feeds/{slug}', [FeedController::class, 'show'])->name('feeds.show')->middleware('auth');

        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index')->middleware('auth');
        Route::get('/agenda/eventos', [AgendaController::class, 'eventosJson'])->name('agenda.eventos.json')->middleware('auth');

        Route::get('/livraria', [LivrariaController::class, 'index'])->name('livraria.index')->middleware(['auth','role:gestor']);
        Route::post('/livraria/search', [LivrariaController::class, 'search'])->name('livraria.search')->middleware(['auth','role:gestor']);

        Route::get('/reunioes', [ReuniaoController::class, 'create'])->name('reunioes.create')->middleware(['auth','role:gestor']);
        Route::get('/reunioes/painel', [ReuniaoController::class, 'index'])->name('reunioes.painel')->middleware(['auth','role:gestor']);
        Route::post('/reunioes', [ReuniaoController::class, 'store'])->name('reunioes.store')->middleware(['auth','role:gestor']);
        Route::get('/reunioes/novo', [ReuniaoController::class, 'form_criar'])->name('reunioes.form_criar')->middleware(['auth','role:gestor']);
        Route::get('/reunioes/editar/{id}', [ReuniaoController::class, 'form_editar'])->name('reunioes.form_editar')->middleware(['auth','role:gestor']);
        Route::put('/reunioes/{reuniao}', [ReuniaoController::class, 'update'])->name('reunioes.update')->middleware(['auth','role:gestor']);
        Route::delete('/reunioes/{reuniao}', [ReuniaoController::class, 'destroy'])->name('reunioes.destroy')->middleware(['auth','role:gestor']);

        Route::get('/pesquisas/painel', [PesquisaController::class, 'painel'])->name('pesquisas.painel')->middleware(['auth','role:gestor']);
        Route::get('/pesquisas/novo', [PesquisaController::class, 'form_criar'])->name('pesquisas.form_criar')->middleware(['auth','role:gestor']);
        Route::get('/pesquisas/editar/{id}', [PesquisaController::class, 'form_editar'])->name('pesquisas.form_editar')->middleware(['auth','role:gestor']);
        Route::post('/pesquisas', [PesquisaController::class, 'store'])->name('pesquisas.store')->middleware(['auth','role:gestor']);
        Route::put('/pesquisas/{id}', [PesquisaController::class, 'update'])->name('pesquisas.update')->middleware(['auth','role:gestor']);
        Route::delete('/pesquisas/{id}', [PesquisaController::class, 'destroy'])->name('pesquisas.destroy')->middleware(['auth','role:gestor']);
        Route::post('/pesquisas/{pesquisa}/perguntas', [PesquisaController::class, 'storePergunta'])->name('pesquisas.perguntas.store')->middleware(['auth','role:gestor']);
        Route::put('/pesquisas/{pesquisa}/perguntas/{pergunta}', [PesquisaController::class, 'updatePergunta'])->name('pesquisas.perguntas.update')->middleware(['auth','role:gestor']);
        Route::delete('/pesquisas/{pesquisa}/perguntas/{pergunta}', [PesquisaController::class, 'destroyPergunta'])->name('pesquisas.perguntas.destroy')->middleware(['auth','role:gestor']);

        Route::get('/avisos/admin', [AvisoController::class, 'index'])->name('avisos.admin')->middleware(['auth','role:gestor']);
        Route::get('/avisos', [AvisoController::class, 'avisosDoMembro'])->name('avisos.painel')->middleware('auth');
        Route::post('/avisos', [AvisoController::class, 'store'])->name('avisos.store')->middleware(['auth','role:gestor']);
        Route::get('/avisos/novo', [AvisoController::class, 'form_criar'])->name('avisos.form_criar')->middleware(['auth','role:gestor']);
        Route::get('/avisos/{aviso}', [AvisoController::class, 'show'])->name('avisos.show')->middleware('auth');

        Route::get('/arquivos/imagens', [ArquivoController::class, 'form_imagens'])->name('arquivos.imagens')->middleware(['auth','role:gestor']);
        Route::post('/arquivos', [ArquivoController::class, 'store'])->name('arquivos.store')->middleware(['auth','role:gestor']);
        Route::delete('/arquivos/{id}', [ArquivoController::class, 'destroy'])->name('arquivos.destroy')->middleware(['auth','role:gestor']);
        Route::get('/arquivos/lista_imagens', [ArquivoController::class, 'lista_imagens'])->name('arquivos.lista_imagens')->middleware(['auth','role:gestor']);

        Route::get('/relatorios', [RelatorioController::class, 'painel'])->name('relatorios.painel')->middleware(['auth','role:gestor']);

        Route::get('/financeiro/caixas/novo', [FinanceiroController::class, 'formCaixa'])->name('financeiro.caixas.form_criar');
        Route::get('/financeiro/caixas/{id}/editar', [FinanceiroController::class, 'formCaixaEditar'])->name('financeiro.caixas.form_editar');
        Route::post('/financeiro/caixas', [FinanceiroController::class, 'storeCaixa'])->name('financeiro.caixas.store');
        Route::put('/financeiro/caixas/{id}', [FinanceiroController::class, 'updateCaixa'])->name('financeiro.caixas.update');
        Route::delete('/financeiro/caixas/{id}', [FinanceiroController::class, 'destroyCaixa'])->name('financeiro.caixas.destroy');

        Route::get('/financeiro/tipos/novo', [FinanceiroController::class, 'formTipo'])->name('financeiro.tipos.form_criar');
        Route::get('/financeiro/tipos/{id}/editar', [FinanceiroController::class, 'formTipoEditar'])->name('financeiro.tipos.form_editar');
        Route::post('/financeiro/tipos', [FinanceiroController::class, 'storeTipo'])->name('financeiro.tipos.store');
        Route::put('/financeiro/tipos/{id}', [FinanceiroController::class, 'updateTipo'])->name('financeiro.tipos.update');
        Route::delete('/financeiro/tipos/{id}', [FinanceiroController::class, 'destroyTipo'])->name('financeiro.tipos.destroy');
        Route::get('/financeiro/lancamentos/{caixa}/novo', [FinanceiroController::class, 'formLancamento'])->name('financeiro.lancamentos.form_criar');
        Route::post('/financeiro/lancamentos', [FinanceiroController::class, 'storeLancamento'])->name('financeiro.lancamentos.store');
        Route::get('/financeiro/lancamentos/{id}/editar', [FinanceiroController::class, 'formLancamentoEditar'])->name('financeiro.lancamentos.form_editar');
        Route::put('/financeiro/lancamentos/{id}', [FinanceiroController::class, 'updateLancamento'])->name('financeiro.lancamentos.update');
        Route::get('/financeiro/lancamentos/export', [FinanceiroController::class, 'exportLancamentos'])->name('financeiro.lancamentos.export');
        Route::get('/financeiro/painel', [FinanceiroController::class, 'painel'])->name('financeiro.painel');
        Route::get('/financeiro/caixas/novo', [FinanceiroController::class, 'formCaixa'])->name('financeiro.caixas.form_criar');

        //Rotas para buscas dinâmicas de localização
        Route::get('/estados/{pais_id}', [LocalizacaoController::class, 'getEstados'])->name('localizacao.estados')->middleware(['auth','role:gestor']);
        Route::get('/cidades/{uf}', [LocalizacaoController::class, 'getCidades'])->name('localizacao.cidades')->middleware(['auth','role:gestor']);

        Route::get('/extensoes', [ExtensoesController::class, 'index'])->name('extensoes.painel')->middleware(['auth','role:gestor']);
        Route::put('/extensoes/{module}', [ExtensoesController::class, 'update'])->name('extensoes.update')->middleware(['auth','role:gestor']);

        Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index')->middleware('auth');

    });
});
