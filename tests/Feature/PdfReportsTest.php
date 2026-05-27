<?php

namespace Tests\Feature;

use App\Http\Middleware\AcessarCongregacaoPeloDominio;
use App\Models\Agrupamento;
use App\Models\Caixa;
use App\Models\Congregacao;
use App\Models\Denominacao;
use App\Models\Dominio;
use App\Models\Evento;
use App\Models\EventoOcorrencia;
use App\Models\LancamentoFinanceiro;
use App\Models\Membro;
use App\Models\TipoLancamento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PdfReportsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(AcessarCongregacaoPeloDominio::class);

        foreach (['gestor', 'membro'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }

    public function test_membros_pdf_respects_filter_and_inactive_scope(): void
    {
        Pdf::fake();

        ['congregacao' => $congregacao] = $this->createCongregacaoContext('igreja.test', 'Igreja Teste');
        ['congregacao' => $otherCongregacao] = $this->createCongregacaoContext('outra.test', 'Outra Igreja');

        $user = $this->createGestorUser($congregacao, 'Gestor Membros', 'gestor-membros@example.test');
        $ministerioId = DB::table('ministerios')->insertGetId([
            'titulo' => 'Louvor',
            'sigla' => 'LVR',
            'descricao' => 'Ministério de Louvor',
            'denominacao_id' => $congregacao->denominacao_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->createMembro($congregacao, [
            'nome' => 'Ativo Igreja',
            'email' => 'ativo@example.test',
            'ativo' => true,
        ]);

        $this->createMembro($congregacao, [
            'nome' => 'Inativo Filtrado',
            'email' => 'membro-inativo-unico@example.test',
            'ativo' => false,
            'ministerio_id' => $ministerioId,
            'data_nascimento' => '1991-05-20',
        ]);

        $this->createMembro($congregacao, [
            'nome' => 'Inativo Outro',
            'email' => 'outro-inativo@example.test',
            'ativo' => false,
        ]);

        $this->createMembro($otherCongregacao, [
            'nome' => 'Inativo Outra Congregação',
            'email' => 'membro-inativo-unico@example.test',
            'ativo' => false,
        ]);

        app()->instance('congregacao', $congregacao);

        $response = $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/membros/imprimir?filtro=email&chave=membro-inativo-unico@example.test&showInactives=1');

        $response->assertOk();

        Pdf::assertRespondedWithPdf(function ($pdf) {
            $membros = $pdf->viewData['membros'];

            return $pdf->viewName === 'membros.relatorios.historico_pdf'
                && $pdf->downloadName === 'relatorio-membros.pdf'
                && $pdf->viewData['showInactives'] === true
                && $membros->count() === 1
                && $membros->first()->nome === 'Inativo Filtrado'
                && $pdf->viewData['resumo']['com_ministerio'] === 1;
        });
    }

    public function test_eventos_historico_pdf_applies_date_filters_and_congregation_scope(): void
    {
        Pdf::fake();

        ['congregacao' => $congregacao] = $this->createCongregacaoContext('igreja.test', 'Igreja Teste');
        ['congregacao' => $otherCongregacao] = $this->createCongregacaoContext('outra.test', 'Outra Igreja');

        $user = $this->createGestorUser($congregacao, 'Gestor Eventos', 'gestor-eventos@example.test');
        $grupo = $this->createAgrupamento($congregacao, 'Jovens');

        $eventoFiltrado = $this->createEvento($congregacao, [
            'titulo' => 'Conferência Jovem',
            'agrupamento_id' => $grupo->id,
            'descricao' => 'Evento que deve entrar no PDF.',
            'data_inicio' => '2026-05-10',
            'data_encerramento' => '2026-05-11',
            'local' => 'Templo Central',
            'requer_inscricao' => true,
        ]);
        $this->createOcorrencia($eventoFiltrado, '2026-05-10', '19:00:00', 'Templo Central', 'Abertura');
        $this->createOcorrencia($eventoFiltrado, '2026-05-11', '18:00:00', 'Templo Central', 'Encerramento');

        $this->createEvento($congregacao, [
            'titulo' => 'Retiro Antigo',
            'data_inicio' => '2026-04-01',
            'data_encerramento' => '2026-04-01',
            'local' => 'Sítio',
        ]);

        $this->createEvento($congregacao, [
            'titulo' => 'Evento Futuro',
            'data_inicio' => '2026-06-15',
            'data_encerramento' => '2026-06-15',
        ]);

        $this->createEvento($otherCongregacao, [
            'titulo' => 'Evento de Outra Congregação',
            'data_inicio' => '2026-05-10',
            'data_encerramento' => '2026-05-10',
        ]);

        app()->instance('congregacao', $congregacao);

        $response = $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/eventos/historico/imprimir?data_inicial=2026-05-01&data_final=2026-05-31');

        $response->assertOk();

        Pdf::assertRespondedWithPdf(function ($pdf) {
            $eventos = $pdf->viewData['eventos'];

            return $pdf->viewName === 'eventos.relatorios.historico_pdf'
                && $pdf->downloadName === 'relatorio-eventos-historico.pdf'
                && $eventos->count() === 1
                && $eventos->first()->titulo === 'Conferência Jovem'
                && $pdf->viewData['resumo']['total_ocorrencias'] === 2
                && $pdf->viewData['resumo']['com_inscricao'] === 1;
        });
    }

    public function test_eventos_agenda_pdf_applies_title_and_group_filters(): void
    {
        Pdf::fake();

        ['congregacao' => $congregacao] = $this->createCongregacaoContext('igreja.test', 'Igreja Teste');
        ['congregacao' => $otherCongregacao] = $this->createCongregacaoContext('outra.test', 'Outra Igreja');

        $user = $this->createGestorUser($congregacao, 'Gestor Agenda', 'gestor-agenda@example.test');
        $grupoJovens = $this->createAgrupamento($congregacao, 'Jovens');
        $grupoLouvor = $this->createAgrupamento($congregacao, 'Louvor');

        $eventoFiltrado = $this->createEvento($congregacao, [
            'titulo' => 'Congresso de Louvor',
            'agrupamento_id' => $grupoLouvor->id,
            'descricao' => 'Evento futuro filtrado.',
            'data_inicio' => '2026-06-20',
            'data_encerramento' => '2026-06-21',
            'local' => 'Auditório',
        ]);
        $this->createOcorrencia($eventoFiltrado, '2026-06-20', '20:00:00', 'Auditório', 'Noite 1');

        $this->createEvento($congregacao, [
            'titulo' => 'Congresso de Louvor',
            'agrupamento_id' => $grupoJovens->id,
            'data_inicio' => '2026-06-22',
            'data_encerramento' => '2026-06-22',
        ]);

        $this->createEvento($congregacao, [
            'titulo' => 'Vigília Geral',
            'agrupamento_id' => $grupoLouvor->id,
            'data_inicio' => '2026-06-25',
            'data_encerramento' => '2026-06-25',
        ]);

        $this->createEvento($otherCongregacao, [
            'titulo' => 'Congresso de Louvor',
            'data_inicio' => '2026-06-20',
            'data_encerramento' => '2026-06-20',
        ]);

        app()->instance('congregacao', $congregacao);

        $response = $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/eventos/agenda/imprimir?titulo=Congresso+de+Louvor&grupo=' . $grupoLouvor->id);

        $response->assertOk();

        Pdf::assertRespondedWithPdf(function ($pdf) use ($grupoLouvor) {
            $eventos = $pdf->viewData['eventos'];

            return $pdf->viewName === 'eventos.relatorios.agenda_pdf'
                && $pdf->downloadName === 'relatorio-eventos-agenda.pdf'
                && $eventos->count() === 1
                && $eventos->first()->titulo === 'Congresso de Louvor'
                && $eventos->first()->grupo_label === 'Louvor'
                && ($pdf->viewData['filtros']['Grupo'] ?? null) === $grupoLouvor->nome;
        });
    }

    public function test_financeiro_pdf_applies_filters_and_builds_summary(): void
    {
        Pdf::fake();

        ['congregacao' => $congregacao] = $this->createCongregacaoContext('igreja.test', 'Igreja Teste');
        ['congregacao' => $otherCongregacao] = $this->createCongregacaoContext('outra.test', 'Outra Igreja');

        $user = $this->createGestorUser($congregacao, 'Gestor Financeiro', 'gestor-financeiro@example.test');

        $caixaPrincipal = Caixa::create([
            'congregacao_id' => $congregacao->id,
            'nome' => 'Caixa Principal',
            'descricao' => 'Principal',
        ]);
        $caixaSecundario = Caixa::create([
            'congregacao_id' => $congregacao->id,
            'nome' => 'Caixa Secundário',
            'descricao' => 'Secundário',
        ]);
        $tipoOferta = TipoLancamento::create([
            'congregacao_id' => $congregacao->id,
            'nome' => 'Oferta',
        ]);
        $tipoDespesa = TipoLancamento::create([
            'congregacao_id' => $congregacao->id,
            'nome' => 'Despesa',
        ]);

        LancamentoFinanceiro::create([
            'caixa_id' => $caixaPrincipal->id,
            'tipo_lancamento_id' => $tipoOferta->id,
            'tipo' => 'entrada',
            'valor' => 150.75,
            'descricao' => 'Oferta do culto',
            'data_lancamento' => '2026-05-10',
        ]);

        LancamentoFinanceiro::create([
            'caixa_id' => $caixaPrincipal->id,
            'tipo_lancamento_id' => $tipoDespesa->id,
            'tipo' => 'saida',
            'valor' => 40.00,
            'descricao' => 'Compra de materiais',
            'data_lancamento' => '2026-05-11',
        ]);

        LancamentoFinanceiro::create([
            'caixa_id' => $caixaSecundario->id,
            'tipo_lancamento_id' => $tipoOferta->id,
            'tipo' => 'entrada',
            'valor' => 999.00,
            'descricao' => 'Não deve aparecer',
            'data_lancamento' => '2026-05-10',
        ]);

        $caixaOutra = Caixa::create([
            'congregacao_id' => $otherCongregacao->id,
            'nome' => 'Caixa Outra',
            'descricao' => 'Outra',
        ]);
        $tipoOutra = TipoLancamento::create([
            'congregacao_id' => $otherCongregacao->id,
            'nome' => 'Oferta',
        ]);
        LancamentoFinanceiro::create([
            'caixa_id' => $caixaOutra->id,
            'tipo_lancamento_id' => $tipoOutra->id,
            'tipo' => 'entrada',
            'valor' => 333.00,
            'descricao' => 'Outra congregação',
            'data_lancamento' => '2026-05-10',
        ]);

        app()->instance('congregacao', $congregacao);

        $response = $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/financeiro/lancamentos/imprimir?caixa=' . $caixaPrincipal->id . '&tipo=entrada&tipo_lancamento_id=' . $tipoOferta->id . '&data_inicio=2026-05-01&data_fim=2026-05-31');

        $response->assertOk();

        Pdf::assertRespondedWithPdf(function ($pdf) {
            $lancamentos = $pdf->viewData['lancamentos'];
            $resumo = $pdf->viewData['resumo'];

            return $pdf->viewName === 'financeiro.relatorios.historico_pdf'
                && $pdf->downloadName === 'relatorio-financeiro.pdf'
                && $lancamentos->count() === 1
                && $lancamentos->first()->descricao === 'Oferta do culto'
                && $resumo['total_lancamentos'] === 1
                && (float) $resumo['total_entradas'] === 150.75
                && (float) $resumo['total_saidas'] === 0.0
                && (float) $resumo['saldo_liquido'] === 150.75;
        });
    }

    public function test_pdf_routes_support_empty_results(): void
    {
        ['congregacao' => $congregacao] = $this->createCongregacaoContext('igreja.test', 'Igreja Teste');
        $user = $this->createGestorUser($congregacao, 'Gestor Vazio', 'gestor-vazio@example.test');

        app()->instance('congregacao', $congregacao);
        Pdf::fake();
        $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/membros/imprimir?filtro=nome&chave=inexistente&showInactives=1')
            ->assertOk();
        Pdf::assertRespondedWithPdf(fn ($pdf) => $pdf->viewName === 'membros.relatorios.historico_pdf' && $pdf->viewData['membros']->isEmpty());

        app()->instance('congregacao', $congregacao);
        Pdf::fake();
        $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/eventos/historico/imprimir?data_inicial=2026-05-01&data_final=2026-05-31')
            ->assertOk();
        Pdf::assertRespondedWithPdf(fn ($pdf) => $pdf->viewName === 'eventos.relatorios.historico_pdf' && $pdf->viewData['eventos']->isEmpty());

        app()->instance('congregacao', $congregacao);
        Pdf::fake();
        $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($user)
            ->get('/financeiro/lancamentos/imprimir?tipo=entrada')
            ->assertOk();
        Pdf::assertRespondedWithPdf(fn ($pdf) => $pdf->viewName === 'financeiro.relatorios.historico_pdf' && $pdf->viewData['lancamentos']->isEmpty());
    }

    private function createCongregacaoContext(string $domain, string $identificacao): array
    {
        $denominacao = new Denominacao();
        $denominacao->nome = 'Denominação ' . $identificacao;
        $denominacao->save();

        DB::table('temas')->insert([
            'id' => DB::table('temas')->max('id') + 1,
            'nome' => 'Tema ' . $identificacao,
            'propriedades' => json_encode(['modo' => 'teste']),
        ]);

        $congregacaoId = DB::table('congregacoes')->insertGetId([
            'denominacao_id' => $denominacao->id,
            'identificacao' => $identificacao,
            'nome_curto' => strtolower(str_replace(' ', '-', $identificacao)),
            'ativa' => true,
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
            'email' => strtolower(str_replace(' ', '.', $identificacao)) . '@example.test',
            'language' => 'pt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('congregacao_configs')->insert([
            'congregacao_id' => $congregacaoId,
            'conjunto_cores' => json_encode([
                'primaria' => '#111111',
                'secundaria' => '#222222',
                'terciaria' => '#333333',
            ]),
            'font_family' => 'Oswald',
            'agrupamentos' => 'grupo',
            'tema_id' => DB::table('temas')->max('id'),
            'links' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $congregacao = Congregacao::findOrFail($congregacaoId);

        $dominio = new Dominio();
        $dominio->congregacao_id = $congregacao->id;
        $dominio->dominio = $domain;
        $dominio->ativo = true;
        $dominio->save();

        app()->instance('congregacao', $congregacao);
        app()->instance('modo_admin', false);
        app()->instance('site_publico', false);

        return ['congregacao' => $congregacao];
    }

    private function createGestorUser(Congregacao $congregacao, string $nome, string $email): User
    {
        app()->instance('congregacao', $congregacao);

        $membro = $this->createMembro($congregacao, [
            'nome' => $nome,
            'email' => $email,
            'ativo' => true,
        ]);

        $user = new User();
        $user->name = strtolower(str_replace(' ', '.', $nome));
        $user->email = $email;
        $user->password = 'password';
        $user->congregacao_id = $congregacao->id;
        $user->denominacao_id = $congregacao->denominacao_id;
        $user->membro_id = $membro->id;
        $user->save();
        $user->assignRole('membro');
        $user->assignRole('gestor');

        return $user;
    }

    private function createMembro(Congregacao $congregacao, array $attributes): Membro
    {
        app()->instance('congregacao', $congregacao);

        $membro = new Membro();
        $membro->congregacao_id = $congregacao->id;
        $membro->nome = $attributes['nome'];
        $membro->telefone = $attributes['telefone'] ?? '11999999999';
        $membro->email = $attributes['email'] ?? null;
        $membro->ativo = $attributes['ativo'] ?? true;
        $membro->ministerio_id = $attributes['ministerio_id'] ?? null;
        $membro->data_nascimento = $attributes['data_nascimento'] ?? null;
        $membro->endereco = $attributes['endereco'] ?? 'Rua Alpha';
        $membro->numero = $attributes['numero'] ?? '100';
        $membro->bairro = $attributes['bairro'] ?? 'Centro';
        $membro->save();

        return $membro;
    }

    private function createAgrupamento(Congregacao $congregacao, string $nome): Agrupamento
    {
        $agrupamento = new Agrupamento();
        $agrupamento->congregacao_id = $congregacao->id;
        $agrupamento->tipo = 'grupo';
        $agrupamento->nome = $nome;
        $agrupamento->save();

        return $agrupamento;
    }

    private function createEvento(Congregacao $congregacao, array $attributes): Evento
    {
        $evento = new Evento();
        $evento->congregacao_id = $congregacao->id;
        $evento->titulo = $attributes['titulo'];
        $evento->agrupamento_id = $attributes['agrupamento_id'] ?? null;
        $evento->descricao = $attributes['descricao'] ?? null;
        $evento->recorrente = $attributes['recorrente'] ?? false;
        $evento->data_inicio = $attributes['data_inicio'];
        $evento->data_encerramento = $attributes['data_encerramento'] ?? $attributes['data_inicio'];
        $evento->local = $attributes['local'] ?? null;
        $evento->requer_inscricao = $attributes['requer_inscricao'] ?? false;
        $evento->save();

        return $evento;
    }

    private function createOcorrencia(Evento $evento, string $data, ?string $horario, ?string $local, ?string $descricao): EventoOcorrencia
    {
        return EventoOcorrencia::create([
            'evento_id' => $evento->id,
            'data_ocorrencia' => $data,
            'horario_inicio' => $horario,
            'local' => $local,
            'descricao' => $descricao,
        ]);
    }
}
