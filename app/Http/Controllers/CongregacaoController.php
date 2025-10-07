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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $congregacao = new Congregacao;

        $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'telefone' => 'required',
        ], [
            '*.required' => 'Nome, Endereço e Telefone são obrigatórios'
        ]);

        $congregacao->denominacao_id = $request->igreja;
        $congregacao->identificacao = $request->nome;
        $congregacao->nome_curto = $request->nome_curto;
        $congregacao->endereco = $request->endereco;
        $congregacao->telefone = $request->telefone;
        $congregacao->email = $request->email;
        $congregacao->cnpj = $request->cnpj;
        $congregacao->ativa = true;
        $congregacao->cidade_id = $request->cidade;
        $congregacao->estado_id = $request->estado;
        $congregacao->pais_id = $request->pais;
        $congregacao->created_at = now();
        $congregacao->updated_at = now();

        if($congregacao->save()){
            // Cria estrutura de diretórios para a congregação
            $basePath = "congregacoes/{$congregacao->id}";

            Storage::makeDirectory("{$basePath}/uploads");
            Storage::makeDirectory("{$basePath}/documentos");
            Storage::makeDirectory("{$basePath}/imagens");
        }

        return redirect()->route('congregacoes.cadastro')->with('msg', 'Congregação cadastrada com sucesso!');
    }

    public function config($id)
    {
        $config = CongregacaoConfig::find($id);

        return view('congregacoes.config', ['config' => $config]);
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
        $congregacao->cnpj = $request->cnpj;
        $congregacao->email = $request->email;
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
