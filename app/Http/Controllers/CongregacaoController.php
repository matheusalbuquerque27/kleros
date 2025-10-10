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
use App\Models\Tema;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CongregacaoController extends Controller
{
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

        $congregacao = DB::transaction(function () use ($validated, $language) {
            $congregacao = new Congregacao();
            $congregacao->denominacao_id = $validated['igreja'];
            $congregacao->identificacao = $validated['nome'];
            $congregacao->nome_curto = $validated['nome_curto'] ?: $validated['nome'];
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

            $slugCurto = Str::slug($congregacao->nome_curto);
            if (empty($slugCurto)) {
                $slugCurto = 'congregacao-' . $congregacao->id;
            }
            $congregacao->nome_curto = $slugCurto;
            $congregacao->save();

            $dominio = new Dominio();
            $dominio->congregacao_id = $congregacao->id;
            $dominio->dominio = "{$slugCurto}.local";
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
                    'font_family' => 'Roboto',
                    'tema_id' => Tema::query()->orderBy('id')->value('id') ?? null,
                    'agrupamentos' => 'grupo',
                    'celulas' => false,
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
            'font_family' => 'Roboto',
            'tema_id' => Tema::query()->orderBy('id')->value('id'),
            'agrupamentos' => 'grupo',
            'celulas' => false,
        ]);

        $temas = Tema::orderBy('nome')->get();
        $fontes = ['Roboto', 'Teko', 'Source Sans Pro', 'Oswald', 'Saira'];

        return view('congregacoes.config', compact('congregacao', 'config', 'temas', 'fontes'));
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
            'font_family' => ['required', 'string', 'max:100'],
            'tema_id' => ['nullable', 'exists:temas,id'],
            'agrupamentos' => ['required', 'in:grupo,departamento,setor'],
            'celulas' => ['required', 'boolean'],
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
        $config->font_family = $validated['font_family'];
        $config->tema_id = $validated['tema_id'] ?? $config->tema_id;
        $config->agrupamentos = $validated['agrupamentos'];
        $config->celulas = (bool) ($validated['celulas'] ?? false);
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

        return view('congregacoes.edicao', [
            'config' => $config,
            'congregacao' => $congregacao,
            'paises' => $paises,
            'fontes' => $fontes,
            'languageOptions' => $languageOptions,
        ]);
    }

    public function update(Request $request, $id){
        
        $congregacao = Congregacao::findOrFail($id);
        $congregacao->identificacao = $request->identificacao;
        $congregacao->nome_curto = $request->nome_curto;
        $congregacao->cnpj = $request->cnpj;
        $congregacao->email = $request->email;
        $congregacao->endereco = $request->endereco;
        $congregacao->numero = $request->numero;
        $congregacao->complemento = $request->complemento;
        $congregacao->bairro = $request->bairro;
        $congregacao->cep = $request->cep;
        $congregacao->telefone = $request->telefone;
        $supportedLocales = Config::get('locales.supported', ['pt', 'en', 'es']);
        $defaultLocale = Config::get('locales.default', Config::get('app.locale', 'pt'));
        $language = in_array($request->language, $supportedLocales, true)
            ? $request->language
            : $defaultLocale;

        $congregacao->cidade_id = $request->cidade;
        $congregacao->estado_id = $request->estado;
        $congregacao->pais_id = $request->pais;
        $congregacao->language = $language;
        $congregacao->save();

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
            // Se o campo logo não for um arquivo, mas uma string (ex: caminho antigo)
            $url = $request->logo_acervo;

            $pos = strpos($url, 'congregacoes/');
            if ($pos !== false) {
                $url = substr($url, $pos); // pega a partir de "congregacoes/"
            }

            $congregacao->config->update([
                'logo_caminho' => $url,
            ]);
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
            // Se o campo logo não for um arquivo, mas uma string (ex: caminho antigo)
            $url = $request->banner_acervo;

            $pos = strpos($url, 'congregacoes/');
            if ($pos !== false) {
                $url = substr($url, $pos); // pega a partir de "congregacoes/"
            }

            $congregacao->config->update([
                'banner_caminho' => $url
            ]);
            
        }
        
         // Atualiza as configurações gerais

        $congregacao->config->update([
            'agrupamentos' => $request->agrupamentos,
            'celulas' => $request->celulas,
            'conjunto_cores' => $request->conjunto_cores,
            'font_family' => $request->font_family,
            'tema_id' => $request->tema,
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
}
