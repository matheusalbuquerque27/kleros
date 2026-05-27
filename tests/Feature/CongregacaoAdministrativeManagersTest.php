<?php

namespace Tests\Feature;

use App\Http\Controllers\CongregacaoController;
use App\Models\Congregacao;
use App\Models\Denominacao;
use App\Models\Dominio;
use App\Models\Membro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CongregacaoAdministrativeManagersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_multiple_administrative_managers_and_finance_roles(): void
    {
        ['congregacao' => $congregacao] = $this->createCongregacaoContext();

        $gestorAtual = $this->createMemberUser($congregacao, 'Gestor Atual', 'gestor-atual@example.test', ['membro', 'gestor', 'principal']);
        $novoGestorA = $this->createMemberUser($congregacao, 'Novo Gestor A', 'novo-gestor-a@example.test', ['membro']);
        $novoGestorB = $this->createMemberUser($congregacao, 'Novo Gestor B', 'novo-gestor-b@example.test', ['membro']);
        $financeiro = $this->createMemberUser($congregacao, 'Financeiro', 'financeiro@example.test', ['membro', 'tesoureiro']);

        $congregacao->responsavel_principal_id = $gestorAtual['membro']->id;
        $congregacao->responsaveis_administrativos = [$gestorAtual['membro']->id];
        $congregacao->responsavel_financeiro = [$financeiro['membro']->id];
        $congregacao->save();

        $response = $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($gestorAtual['user'])
            ->put('/configuracoes/' . $congregacao->id, [
                'identificacao' => $congregacao->identificacao,
                'nome_curto' => $congregacao->nome_curto,
                'email' => $congregacao->email,
                'endereco' => $congregacao->endereco,
                'telefone' => $congregacao->telefone,
                'language' => 'pt',
                'agrupamentos' => 'grupo',
                'conjunto_cores' => [
                    'primaria' => '#111111',
                    'secundaria' => '#222222',
                    'terciaria' => '#333333',
                ],
                'responsaveis_administrativos' => [
                    $novoGestorA['membro']->id,
                    $novoGestorB['membro']->id,
                ],
                'responsavel_financeiro' => [
                    $novoGestorB['membro']->id,
                ],
            ]);

        $response->assertRedirect();

        $congregacao->refresh();
        $this->assertSame(
            [$novoGestorA['membro']->id, $novoGestorB['membro']->id],
            $congregacao->responsaveisAdministrativosIds()
        );
        $this->assertSame($novoGestorA['membro']->id, $congregacao->responsavel_principal_id);
        $this->assertSame([$novoGestorB['membro']->id], $congregacao->responsavel_financeiro);

        $gestorAtual['user']->refresh();
        $novoGestorA['user']->refresh();
        $novoGestorB['user']->refresh();
        $financeiro['user']->refresh();

        $this->assertFalse($gestorAtual['user']->hasRole('gestor'));
        $this->assertFalse($gestorAtual['user']->hasRole('principal'));
        $this->assertTrue($gestorAtual['user']->hasRole('membro'));

        $this->assertTrue($novoGestorA['user']->hasRole('gestor'));
        $this->assertTrue($novoGestorA['user']->hasRole('principal'));

        $this->assertTrue($novoGestorB['user']->hasRole('gestor'));
        $this->assertTrue($novoGestorB['user']->hasRole('principal'));
        $this->assertTrue($novoGestorB['user']->hasRole('tesoureiro'));

        $this->assertFalse($financeiro['user']->hasRole('tesoureiro'));

        $this->withServerVariables(['HTTP_HOST' => 'igreja.test'])
            ->actingAs($gestorAtual['user'])
            ->get('/cadastros')
            ->assertStatus(302);
    }

    public function test_it_prefills_administrative_selection_from_legacy_principal_field(): void
    {
        ['congregacao' => $congregacao] = $this->createCongregacaoContext();

        $gestorLegado = $this->createMemberUser($congregacao, 'Gestor Legado', 'gestor-legado@example.test', ['membro', 'gestor', 'principal']);
        $outroGestor = $this->createMemberUser($congregacao, 'Outro Gestor', 'outro-gestor@example.test', ['membro']);

        $congregacao->responsavel_principal_id = $gestorLegado['membro']->id;
        $congregacao->responsaveis_administrativos = null;
        $congregacao->save();

        $this->actingAs($gestorLegado['user']);
        $view = app(CongregacaoController::class)->editar($congregacao->id);
        $data = $view->getData();

        $this->assertSame([$gestorLegado['membro']->id], $data['responsaveisAdministrativosSelecionados']);

        $ids = $data['membrosComUsuario']->pluck('id')->all();
        $this->assertContains($gestorLegado['membro']->id, $ids);
        $this->assertContains($outroGestor['membro']->id, $ids);
    }

    /**
     * @return array{congregacao: Congregacao}
     */
    private function createCongregacaoContext(): array
    {
        foreach (['gestor', 'membro', 'principal', 'tesoureiro'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $denominacao = new Denominacao();
        $denominacao->nome = 'Denominação Teste';
        $denominacao->save();

        $now = now();

        DB::table('temas')->insert([
            'id' => 1,
            'nome' => 'Tema padrão',
            'propriedades' => json_encode(['modo' => 'teste']),
        ]);

        $congregacaoId = DB::table('congregacoes')->insertGetId([
            'denominacao_id' => $denominacao->id,
            'identificacao' => 'Congregação Teste',
            'nome_curto' => 'igreja',
            'ativa' => true,
            'endereco' => 'Rua Teste',
            'telefone' => '11999999999',
            'email' => 'contato@example.test',
            'language' => 'pt',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('congregacao_configs')->insert([
            'congregacao_id' => $congregacaoId,
            'conjunto_cores' => json_encode([
                'primaria' => '#6449a2',
                'secundaria' => '#1a1821',
                'terciaria' => '#cbb6ff',
            ]),
            'font_family' => 'Oswald',
            'agrupamentos' => 'grupo',
            'links' => json_encode([]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $congregacao = Congregacao::findOrFail($congregacaoId);

        $dominio = new Dominio();
        $dominio->congregacao_id = $congregacao->id;
        $dominio->dominio = 'igreja.test';
        $dominio->ativo = true;
        $dominio->save();

        app()->instance('congregacao', $congregacao);
        app()->instance('modo_admin', false);
        app()->instance('site_publico', false);

        return ['congregacao' => $congregacao];
    }

    /**
     * @param  list<string>  $roles
     * @return array{membro: Membro, user: User}
     */
    private function createMemberUser(Congregacao $congregacao, string $nome, string $email, array $roles): array
    {
        app()->instance('congregacao', $congregacao);

        $membro = new Membro();
        $membro->nome = $nome;
        $membro->telefone = '11999999999';
        $membro->email = $email;
        $membro->ativo = true;
        $membro->save();

        $user = new User();
        $user->name = strtolower(str_replace(' ', '.', $nome));
        $user->email = $email;
        $user->password = 'password';
        $user->congregacao_id = $congregacao->id;
        $user->denominacao_id = $congregacao->denominacao_id;
        $user->membro_id = $membro->id;
        $user->save();

        foreach ($roles as $role) {
            $user->assignRole($role);
        }

        return ['membro' => $membro, 'user' => $user];
    }
}
