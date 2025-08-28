<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Feed;
use Carbon\Carbon;

class AtualizarFeedsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $feeds = [
            'btcast' => 'https://bibotalk.com/feed/',
            'cafecf' => 'https://feeds.libsyn.com/466317/rss',
            'gospel+' => 'https://noticias.gospelmais.com/feed',
            'guiame' => 'https://feeds.feedburner.com/guiame',
            'missoesnacionais' => 'https://missoesnacionais.org.br/noticias/feed/',
        ];

        foreach ($feeds as $nome => $url) {
            try {
                $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);

                foreach ($xml->channel->item as $item) {
                    $titulo = (string) $item->title;
                    $link = (string) $item->link;
                    $descricao = (string) ($item->description ?? null);
                    $conteudo = (string) ($item->children('content', true)->encoded ?? $descricao);

                    $pubDate = isset($item->pubDate) ? Carbon::parse($item->pubDate) : now();
                    $slug = Str::slug($titulo.'-'.md5($link));

                    // 🔑 categoria
                    $categoria = in_array($nome, ['btcast','cafecf']) ? 'podcast' : 'noticia';

                    // 🔑 áudio
                    $audio = null;
                    if (isset($item->enclosure) && $item->enclosure['url']) {
                        $audioPull = (string) $item->enclosure['url'];
                        $audio = strtok($audioPull, '?');
                    }

                    // 🔑 imagem
                    $imagem = null;
                    $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                    $media  = $item->children('http://search.yahoo.com/mrss/');

                    if ($itunes && $itunes->image && $itunes->image->attributes()->href) {
                        $imagem = (string) $itunes->image->attributes()->href;
                    } elseif ($media && $media->thumbnail && $media->thumbnail->attributes()->url) {
                        $imagem = (string) $media->thumbnail->attributes()->url;
                    } elseif (isset($xml->channel->image->url)) {
                        $imagem = (string) $xml->channel->image->url;
                    }

                    // 🔍 log pra depuração
                    Log::info("Feed processado", [
                        'fonte'  => $nome,
                        'titulo' => $titulo,
                        'audio'  => $audio,
                        'imagem' => $imagem,
                    ]);

                    Feed::updateOrCreate(
                        ['slug' => $slug],
                        array_filter([
                        'titulo'       => $titulo,
                        'link'         => $link,
                        'descricao'    => strip_tags($descricao),
                        'conteudo'     => $conteudo,
                        'imagem_capa'  => $imagem,
                        'fonte'        => $nome,
                        'tipo'         => 'rss',
                        'categoria'    => $categoria,
                        'publicado_em' => $pubDate,
                        'audio_url'    => $audio,
                    ], fn($v) => !is_null($v))
                    );
                }
            } catch (\Exception $e) {
                Log::error("Erro ao atualizar feed {$nome}: ".$e->getMessage());
            }
        }
    }
}