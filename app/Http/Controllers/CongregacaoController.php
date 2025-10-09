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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Dominio;
use Illuminate\Support\Str;

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
        $validated = $request->validate([
            'igreja' => ['required', 'exists:denominacoes,id'],
            'nome' => ['required', 'string', 'max:255'],
            'nome_curto' => ['nullable', 'string', 'max:255'],
            'endereco' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'cnpj' => ['nullable', 'string', 'max:32'],
            'cidade' => ['nullable', 'exists:cidades,id'],
            'estado' => ['nullable', 'exists:estados,id'],
            'pais' => ['nullable', 'exists:paises,id'],
            'numero' => ['nullable', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:120'],
            'bairro' => ['nullable', 'string', 'max:120'],
            'cep' => ['nullable', 'string', 'max:20'],
        ], [
            'nome.required' => 'Informe o nome da congregação.',
            'endereco.required' => 'Informe o endereço da congregação.',
            'telefone.required' => 'Informe um telefone de contato.',
        ]);

        $congregacao = DB::transaction(function () use ($validated) {
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

            return $congregacao;
        });

        $basePath = "congregacoes/{$congregacao->id}";
        Storage::makeDirectory("{$basePath}/uploads");
        Storage::makeDirectory("{$basePath}/documentos");
        Storage::makeDirectory("{$basePath}/imagens");

        return redirect()
            ->route('congregacoes.config', $congregacao->id)
            ->with('config_intro', 'Primeira etapa concluída! Personalize a congregação e finalize o cadastro.');
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

        return redirect()
            ->route('congregacoes.config', $congregacao->id)
            ->with('msg', 'Configurações personalizadas com sucesso! Sua congregação está pronta para começar.');
    }

    public function editar($id)
    {
        $congregacao = app('congregacao');
        $config = CongregacaoConfig::find($id);
        $paises = Pais::all();

        return view('congregacoes.edicao', ['config' => $config, 'congregacao' => $congregacao, 'paises' => $paises]);
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
        $congregacao->cidade_id = $request->cidade;
        $congregacao->estado_id = $request->estado;
        $congregacao->pais_id = $request->pais;
        $congregacao->save();

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
            'font_family' => $request->fonte,
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
