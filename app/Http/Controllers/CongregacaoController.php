<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use App\Models\Congregacao;
use App\Models\CongregacaoConfig;
use App\Models\Denominacao;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Pais;
use App\Models\Membro;
use App\Models\User;
use App\Mail\CongregacaoGestorBoasVindas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Dominio;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CongregacaoController extends Controller
{
    private function normalizeDomainSlug(?string $value, int $congregacaoId = 0): string
    {
        $slug = Str::of($value ?? '')
            ->ascii()
            ->lower()
            ->replaceMatches('/\s+/', '')
            ->value();

        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'congregacao' . $congregacaoId;
    }

    private function buildCongregationDomain(string $slug): string
    {
        $publicDomain = (string) config('domains.public', 'kleros.local');
        $isProduction = app()->environment('production');

        if ($isProduction) {
            return "{$slug}.{$publicDomain}";
        }

        return "{$slug}.local";
    }

    public function index()
    {   
        $congregacoes = Congregacao::all();

        return view('congregacoes.perfil', ['congregacoes' => $congregacoes]);
    }

    public function create()
    {
        $denominacoes = Denominacao::orderBy('nome')->get();
        $estados = Estado::orderBy('nome')->get();
        $cidades = Cidade::orderBy('nome')->get();
        $paises = Pais::orderBy('nome')->get();

        return view('congregacoes.cadastro', [
            'denominacoes' => $denominacoes,
            'estados' => $estados,
            'cidades' => $cidades,
            'paises' => $paises,
        ]);
    }

    public function store(Request $request)
    {
        $supportedLocales = Config::get('locales.supported', []);
        $validated = $request->validate([
            'igreja' => ['required', 'exists:denominacoes,id'],
            'nome' => ['required', 'string', 'max:255'],
            'nome_curto' => ['nullable', 'string', 'max:255'],
            'endereco' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'cnpj' => ['nullable', 'string', 'max:32'],
            'cidade' => ['nullable', 'exists:cidades,id'],
            'estado' => ['nullable', 'exists:estados,id'],
            'pais' => ['nullable', 'exists:paises,id'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:120'],
            'bairro' => ['nullable', 'string', 'max:120'],
            'cep' => ['nullable', 'string', 'max:20'],
            'language' => ['nullable', 'string', Rule::in($supportedLocales)],
            'gestor_nome' => ['required', 'string', 'max:255'],
            'gestor_telefone' => ['required', 'string', 'max:50'],
            'gestor_data_nascimento' => ['required', 'date'],
            'gestor_cpf' => ['required', 'string', 'max:20'],
        ], [
            'nome.required' => __('congregations.validation.nome_required'),
            'endereco.required' => __('congregations.validation.endereco_required'),
            'telefone.required' => __('congregations.validation.telefone_required'),
            'email.required' => __('congregations.validation.email_required'),
            'email.unique' => __('congregations.validation.email_unique'),
            'gestor_nome.required' => __('congregations.validation.gestor_nome_required'),
            'gestor_telefone.required' => __('congregations.validation.gestor_telefone_required'),
            'gestor_data_nascimento.required' => __('congregations.validation.gestor_data_nascimento_required'),
            'gestor_cpf.required' => __('congregations.validation.gestor_cpf_required'),
        ]);

        $language = $validated['language']
            ?? $request->session()->get('app_locale')
            ?? app()->getLocale();

        if (!in_array($language, $supportedLocales, true)) {
            $language = Config::get('locales.default', Config::get('app.locale', 'pt'));
        }

        $nomeCurtoBase = $validated['nome_curto'] ?: $validated['nome'];
        $slugCurto = $this->normalizeDomainSlug($nomeCurtoBase);

        $fullDomain = $this->buildCongregationDomain($slugCurto);

        if (Dominio::query()->where('dominio', $fullDomain)->exists()) {
            throw ValidationException::withMessages([
                'nome_curto' => __('congregations.validation.nome_curto_unique'),
            ]);
        }

        $congregacao = DB::transaction(function () use ($validated, $language, $slugCurto, $fullDomain) {
            $congregacao = new Congregacao();
            $congregacao->denominacao_id = $validated['igreja'];
            $congregacao->identificacao = $validated['nome'];
            $congregacao->nome_curto = $validated['nome_curto'] ?? null;
            $congregacao->endereco = $validated['endereco'];
            $congregacao->numero = $validated['numero'] ?? null;
            $congregacao->complemento = $validated['complemento'] ?? null;
            $congregacao->bairro = $validated['bairro'] ?? null;
            $congregacao->cep = $validated['cep'] ?? null;
            $congregacao->telefone = $validated['telefone'];
            $congregacao->email = $validated['email'] ?? null;
            $congregacao->cnpj = $validated['cnpj'] ?? null;
            $congregacao->ativa = true;
            $congregacao->cidade_id = $validated['cidade'] ?? null;
            $congregacao->estado_id = $validated['estado'] ?? null;
            $congregacao->pais_id = $validated['pais'] ?? null;
            $congregacao->language = $language;
            $congregacao->save();

            $dominio = new Dominio();
            $dominio->congregacao_id = $congregacao->id;
            $dominio->dominio = $fullDomain;
            $dominio->ativo = true;
            $dominio->save();

            CongregacaoConfig::firstOrCreate(
                ['congregacao_id' => $congregacao->id],
                [
                    'logo_caminho' => null,
                    'banner_caminho' => null,
                    'conjunto_cores' => [
                        'primaria' => '#6449a2',
                        'secundaria' => '#1a1821',
                        'terciaria' => '#cbb6ff',
                    ],
                    'font_family' => 'Oswald',
                    'tema_id' => 1,
                    'agrupamentos' => 'grupo',
                ]
            );

            $gestorNascimento = Carbon::parse($validated['gestor_data_nascimento']);

            $previousCongregacao = app()->bound('congregacao') ? app('congregacao') : null;
            app()->instance('congregacao', $congregacao);

            $membroGestor = new Membro();
            $membroGestor->congregacao_id = $congregacao->id;
            $membroGestor->nome = $validated['gestor_nome'];
            $membroGestor->telefone = $validated['gestor_telefone'];
            $membroGestor->cpf = preg_replace('/\D+/', '', $validated['gestor_cpf']);
            $membroGestor->data_nascimento = $gestorNascimento;
            $membroGestor->email = $validated['email'];
            $membroGestor->ativo = true;
            $membroGestor->save();

            if ($previousCongregacao) {
                app()->instance('congregacao', $previousCongregacao);
            } else {
                app()->forgetInstance('congregacao');
            }

            $usuarioGestor = new User();
            $usuarioGestor->name = '';
            $usuarioGestor->email = $validated['email'];
            $usuarioGestor->password = Hash::make(Str::random(32));
            $usuarioGestor->congregacao_id = $congregacao->id;
            $usuarioGestor->denominacao_id = $validated['igreja'];
            $usuarioGestor->membro_id = $membroGestor->id;
            $usuarioGestor->save();

            if (! $usuarioGestor->hasRole('gestor')) {
                $usuarioGestor->assignRole('gestor');
            }

            $nomePartes = preg_split('/\s+/', trim($membroGestor->nome)) ?: [];
            $primeiroNome = $nomePartes[0] ?? 'gestor';
            $ultimoNome = $nomePartes[count($nomePartes) - 1] ?? $primeiroNome;

            $normalizar = static function (string $valor): string {
                $ascii = Str::lower(Str::ascii($valor));
                $limpo = preg_replace('/[^a-z0-9]/', '', $ascii ?? '');

                return $limpo !== '' ? $limpo : 'gestor';
            };

            $primeiroSegmento = $normalizar($primeiroNome);
            $ultimoSegmento = $normalizar($ultimoNome);

            $usuarioGestor->name = "{$primeiroSegmento}.{$ultimoSegmento}{$usuarioGestor->id}";
            $usuarioGestor->save();

            return $congregacao;
        });

        $basePath = "congregacoes/{$congregacao->id}";
        Storage::makeDirectory("{$basePath}/uploads");
        Storage::makeDirectory("{$basePath}/documentos");
        Storage::makeDirectory("{$basePath}/imagens");

        return redirect()
            ->route('congregacoes.config', $congregacao->id)
            ->with('config_intro', __('congregations.config.intro'));
    }

    public function config($congregacaoId)
    {
        $congregacao = Congregacao::with('config')->findOrFail($congregacaoId);
        $config = $congregacao->config ?: CongregacaoConfig::create([
            'congregacao_id' => $congregacao->id,
            'conjunto_cores' => [
                'primaria' => '#6449a2',
                'secundaria' => '#1a1821',
                'terciaria' => '#cbb6ff',
            ],
            'font_family' => 'Oswald',
            'tema_id' => 1,
            'agrupamentos' => 'grupo',
        ]);

        return view('congregacoes.config', compact('congregacao', 'config'));
    }

    public function salvarConfig(Request $request, $congregacaoId)
    {
        $congregacao = Congregacao::with('config')->findOrFail($congregacaoId);
        $config = $congregacao->config ?: new CongregacaoConfig(['congregacao_id' => $congregacao->id]);

        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
            'conjunto_cores.primaria' => ['required'],
            'conjunto_cores.secundaria' => ['required'],
            'conjunto_cores.terciaria' => ['required'],
            'agrupamentos' => ['required', 'in:grupo,departamento,setor'],
            'links' => ['nullable', 'array'],
            'links.*' => ['nullable', 'url'],
        ]);

        $logoPath = $config->logo_caminho;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')
                ->store("congregacoes/{$congregacao->id}/imagens", 'public');

            $arquivo = new Arquivo();
            $arquivo->nome = $request->file('logo')->getClientOriginalName();
            $arquivo->caminho = $logoPath;
            $arquivo->tipo = 'imagem';
            $arquivo->congregacao_id = $congregacao->id;
            $arquivo->save();
        }

        $bannerPath = $config->banner_caminho;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')
                ->store("congregacoes/{$congregacao->id}/imagens", 'public');

            $arquivo = new Arquivo();
            $arquivo->nome = $request->file('banner')->getClientOriginalName();
            $arquivo->caminho = $bannerPath;
            $arquivo->tipo = 'imagem';
            $arquivo->congregacao_id = $congregacao->id;
            $arquivo->save();
        }

        $config->logo_caminho = $logoPath;
        $config->banner_caminho = $bannerPath;
        $config->conjunto_cores = $validated['conjunto_cores'];
        $config->agrupamentos = $validated['agrupamentos'];
        $links = array_filter($request->input('links', []), fn ($url) => filled($url));
        $config->links = $links ?: [];
        $config->save();

        $messageKey = 'congregations.config.success';

        if (! $congregacao->gestor_notificado_em) {
            $gestorUser = User::query()
                ->where('congregacao_id', $congregacao->id)
                ->role('gestor')
                ->orderBy('id')
                ->first();

            if ($gestorUser) {
                $temporaryPassword = Str::random(12);
                $gestorUser->password = Hash::make($temporaryPassword);
                $gestorUser->save();

                $gestorUser->loadMissing('membro');
                $congregacao->loadMissing('denominacao', 'cidade', 'estado');

                try {
                    Mail::to($gestorUser->email)->send(
                        new CongregacaoGestorBoasVindas(
                            $congregacao,
                            $gestorUser,
                            $gestorUser->membro,
                            $temporaryPassword
                        )
                    );

                    $congregacao->gestor_notificado_em = now();
                    $congregacao->save();
                } catch (\Throwable $exception) {
                    Log::error('Falha ao enviar e-mail de boas-vindas ao gestor.', [
                        'congregacao_id' => $congregacao->id,
                        'gestor_user_id' => $gestorUser->id,
                        'exception' => $exception->getMessage(),
                    ]);
                    $messageKey = 'congregations.config.success_no_email';
                }
            } else {
                $messageKey = 'congregations.config.success_no_email';
            }
        }

        return redirect()
            ->route('congregacoes.config', $congregacao->id)
            ->with('msg', __($messageKey));
    }

    public function editar($id)
    {
        $congregacao = Congregacao::with('config')->findOrFail($id);
        $config = $congregacao->config ?: CongregacaoConfig::firstOrCreate(['congregacao_id' => $congregacao->id]);
        $paises = Pais::orderBy('nome')->get();
        $fontes = ['Roboto', 'Teko', 'Source Sans Pro', 'Oswald', 'Saira'];
        $supportedLocales = Config::get('locales.supported', ['pt', 'en', 'es']);
        $localeLabels = Config::get('locales.labels', []);
        $languageOptions = [];

        foreach ($supportedLocales as $locale) {
            $languageOptions[$locale] = $localeLabels[$locale] ?? strtoupper($locale);
        }

        // Buscar todos os membros da congregação
        $membros = Membro::with('user')
            ->where('congregacao_id', $congregacao->id)
            ->orderBy('nome')
            ->get();

        $membrosComUsuario = $membros->filter(fn (Membro $membro) => $membro->user !== null)->values();
        $responsaveisAdministrativosSelecionados = old(
            'responsaveis_administrativos',
            $congregacao->responsaveisAdministrativosIds()
        );

        return view('congregacoes.edicao', [
            'config' => $config,
            'congregacao' => $congregacao,
            'paises' => $paises,
            'fontes' => $fontes,
            'languageOptions' => $languageOptions,
            'membros' => $membros,
            'membrosComUsuario' => $membrosComUsuario,
            'responsaveisAdministrativosSelecionados' => $responsaveisAdministrativosSelecionados,
        ]);
    }

    public function update(Request $request, $id){
        $congregacao = Congregacao::findOrFail($id);
        $supportedLocales = Config::get('locales.supported', ['pt', 'en', 'es']);
        $defaultLocale = Config::get('locales.default', Config::get('app.locale', 'pt'));
        $validated = $request->validate([
            'identificacao' => ['nullable', 'string', 'max:255'],
            'nome_curto' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:120'],
            'bairro' => ['nullable', 'string', 'max:120'],
            'cep' => ['nullable', 'string', 'max:20'],
            'telefone' => ['nullable', 'string', 'max:50'],
            'language' => ['nullable', 'string', Rule::in($supportedLocales)],
            'cidade' => ['nullable', 'exists:cidades,id'],
            'estado' => ['nullable', 'exists:estados,id'],
            'pais' => ['nullable', 'exists:paises,id'],
            'agrupamentos' => ['nullable', 'in:grupo,departamento,setor'],
            'font_family' => ['nullable', 'string', 'max:255'],
            'tema' => ['nullable', 'integer', 'exists:temas,id'],
            'conjunto_cores' => ['nullable', 'array'],
            'conjunto_cores.primaria' => ['nullable', 'string'],
            'conjunto_cores.secundaria' => ['nullable', 'string'],
            'conjunto_cores.terciaria' => ['nullable', 'string'],
            'links' => ['nullable', 'array'],
            'links.*' => ['nullable', 'string'],
            'responsaveis_administrativos' => ['nullable', 'array'],
            'responsaveis_administrativos.*' => [
                'integer',
                function ($attribute, $value, $fail) use ($congregacao) {
                    $membroValido = Membro::query()
                        ->where('congregacao_id', $congregacao->id)
                        ->where('id', $value)
                        ->whereHas('user')
                        ->exists();

                    if (! $membroValido) {
                        $fail('Responsável administrativo inválido para esta congregação.');
                    }
                },
            ],
            'responsavel_financeiro' => ['nullable', 'array'],
            'responsavel_financeiro.*' => [
                'integer',
                function ($attribute, $value, $fail) use ($congregacao) {
                    $membroValido = Membro::query()
                        ->where('congregacao_id', $congregacao->id)
                        ->where('id', $value)
                        ->exists();

                    if (! $membroValido) {
                        $fail('Responsável financeiro inválido para esta congregação.');
                    }
                },
            ],
        ]);

        $language = in_array($validated['language'] ?? null, $supportedLocales, true)
            ? $validated['language']
            : $defaultLocale;

        $responsaveisAdministrativos = $this->sanitizeResponsaveis($validated['responsaveis_administrativos'] ?? null);
        $responsaveisFinanceiros = $this->sanitizeResponsaveis($validated['responsavel_financeiro'] ?? null);
        $responsavelPrincipalId = $responsaveisAdministrativos[0] ?? null;
        $links = $request->input('links', []);

        if (! is_array($links)) {
            $links = [];
        }

        $links = array_filter(array_map('trim', $links), fn ($url) => $url !== '');

        $responsaveisAdministrativosAnteriores = $congregacao->responsaveisAdministrativosIds();
        $responsaveisFinanceirosAnteriores = $this->sanitizeResponsaveis($congregacao->responsavel_financeiro);

        $congregacao->identificacao = $validated['identificacao'] ?? null;
        $congregacao->nome_curto = $validated['nome_curto'] ?? null;
        $congregacao->cnpj = $validated['cnpj'] ?? null;
        $congregacao->email = $validated['email'] ?? null;
        $congregacao->endereco = $validated['endereco'] ?? null;
        $congregacao->numero = $validated['numero'] ?? null;
        $congregacao->complemento = $validated['complemento'] ?? null;
        $congregacao->bairro = $validated['bairro'] ?? null;
        $congregacao->cep = $validated['cep'] ?? null;
        $congregacao->telefone = $validated['telefone'] ?? null;
        $congregacao->cidade_id = $validated['cidade'] ?? null;
        $congregacao->estado_id = $validated['estado'] ?? null;
        $congregacao->pais_id = $validated['pais'] ?? null;
        $congregacao->language = $language;
        $congregacao->responsavel_principal_id = $responsavelPrincipalId;
        $congregacao->responsaveis_administrativos = $responsaveisAdministrativos;
        $congregacao->responsavel_financeiro = $responsaveisFinanceiros;
        $congregacao->save();

        $this->syncAdministrativeRoles($responsaveisAdministrativosAnteriores, $responsaveisAdministrativos);
        $this->syncTesoureiroRoles($responsaveisFinanceirosAnteriores, $responsaveisFinanceiros);

        $request->session()->put('app_locale', $language);
        app()->setLocale($language);

        if ($request->hasFile('logo')) {
            // Salva o arquivo e pega o caminho (ex: 'logos/abcd1234.png')
            $path = $request->file('logo')->store('congregacoes/' . app('congregacao')->id . '/imagens', 'public');

            // Atualiza o campo no banco de dados
            $congregacao->config->update([
                'logo_caminho' => $path, // salva o caminho completo relativo à pasta storage
            ]);

            $arquivo = new Arquivo();
            $arquivo->nome = $request->file('logo')->getClientOriginalName();
            $arquivo->caminho = $path;
            $arquivo->tipo = 'imagem';
            $arquivo->congregacao_id = app('congregacao')->id;
            $arquivo->save();


        } else if ($request->logo_acervo) {
            // Se o usuário selecionou uma imagem do acervo (Drive)
            $arquivoId = $request->logo_acervo;
            $arquivo = Arquivo::find($arquivoId);
            
            if ($arquivo) {
                $congregacao->config->update([
                    'logo_caminho' => $arquivo->caminho,
                ]);
            }
        }

        if ($request->hasFile('banner')) {
            // Salva o arquivo e pega o caminho (ex: 'logos/abcd1234.png')
            $path = $request->file('banner')->store('congregacoes/' . app('congregacao')->id . '/imagens', 'public');

            // Atualiza o campo no banco de dados
            $congregacao->config->update([
                'banner_caminho' => $path, // salva o caminho completo relativo à pasta storage
            ]);

            $arquivo = new Arquivo();
            $arquivo->nome = $request->file('banner')->getClientOriginalName();
            $arquivo->caminho = $path;
            $arquivo->tipo = 'imagem';
            $arquivo->congregacao_id = app('congregacao')->id;
            $arquivo->save();

        } else if ($request->banner_acervo) {
            // Se o usuário selecionou uma imagem do acervo (Drive)
            $arquivoId = $request->banner_acervo;
            $arquivo = Arquivo::find($arquivoId);
            
            if ($arquivo) {
                $congregacao->config->update([
                    'banner_caminho' => $arquivo->caminho,
                ]);
            }
        }
        
         // Atualiza as configurações gerais

        $congregacao->config->update([
            'agrupamentos' => $validated['agrupamentos'] ?? $congregacao->config->agrupamentos,
            'conjunto_cores' => $validated['conjunto_cores'] ?? $congregacao->config->conjunto_cores,
            'font_family' => $validated['font_family'] ?? $congregacao->config->font_family,
            'tema_id' => $validated['tema'] ?? $congregacao->config->tema_id,
            'links' => $links,
        ]);

        return redirect()->back()->with('msg', 'Configurações gerais foram alteradas com sucesso.');

    }

    public function destroy($id)
    {
        // $congregacao = Congregacao::findOrFail($id);

        // // Verifica se a congregação existe
        // if (!$congregacao) {
        //     return redirect()->back()->with('error', 'Congregação não encontrada.');
        // }

        // // Deleta os arquivos associados à congregação
        // Storage::deleteDirectory("congregacoes/{$congregacao->id}");

        // // Deleta a congregação do banco de dados
        // $congregacao->delete();

        //return redirect()->route('congregacoes.index')->with('success', 'Congregação excluída com sucesso.');
    }

    private function sanitizeResponsaveis($responsaveis): array
    {
        if (! is_array($responsaveis)) {
            return [];
        }

        return collect($responsaveis)
            ->filter(fn ($id) => ! is_null($id) && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function syncAdministrativeRoles(array $previousIds, array $newIds): void
    {
        $addedIds = array_values(array_diff($newIds, $previousIds));
        $removedIds = array_values(array_diff($previousIds, $newIds));

        if ($addedIds) {
            $membrosAdicionados = Membro::with('user')->whereIn('id', $addedIds)->get();

            foreach ($membrosAdicionados as $membro) {
                $user = $membro->user;

                if (! $user) {
                    continue;
                }

                if (! $user->hasRole('membro')) {
                    $user->assignRole('membro');
                }

                foreach (['gestor', 'principal'] as $role) {
                    if (! $user->hasRole($role)) {
                        $user->assignRole($role);
                    }
                }
            }
        }

        if ($removedIds) {
            $membrosRemovidos = Membro::with('user')->whereIn('id', $removedIds)->get();

            foreach ($membrosRemovidos as $membro) {
                $user = $membro->user;

                if (! $user) {
                    continue;
                }

                foreach (['gestor', 'principal'] as $role) {
                    if ($user->hasRole($role)) {
                        $user->removeRole($role);
                    }
                }

                if (! $user->hasRole('membro')) {
                    $user->assignRole('membro');
                }
            }
        }
    }

    private function syncTesoureiroRoles(array $previousIds, array $newIds): void
    {
        $addedIds = array_values(array_diff($newIds, $previousIds));
        $removedIds = array_values(array_diff($previousIds, $newIds));

        if ($addedIds) {
            $membrosAdicionados = Membro::with('user')->whereIn('id', $addedIds)->get();

            foreach ($membrosAdicionados as $membro) {
                if ($membro->user && ! $membro->user->hasRole('tesoureiro')) {
                    $membro->user->assignRole('tesoureiro');
                }
            }
        }

        if ($removedIds) {
            $membrosRemovidos = Membro::with('user')->whereIn('id', $removedIds)->get();

            foreach ($membrosRemovidos as $membro) {
                if ($membro->user && $membro->user->hasRole('tesoureiro')) {
                    $membro->user->removeRole('tesoureiro');
                }
            }
        }
    }
}
