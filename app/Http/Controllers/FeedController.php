<?php 

namespace App\Http\Controllers;

use FeedIo\FeedIo;
use FeedIo\Adapter\Http\Client;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Psr\Log\NullLogger;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    public function noticias()
    {
        $noticias = Cache::remember('noticias_feed', 1800, function(){

            $canais = [
                'gospel+' => 'https://noticias.gospelmais.com/feed', 
                'guiame' => 'https://feeds.feedburner.com/guiame'
            ];

            foreach ($canais as $name => $feed) {
                $httpClient = new Client(new GuzzleAdapter(new GuzzleClient()));
                $logger = new NullLogger();
                $feedIo = new FeedIo($httpClient, $logger);

                $url = $feed;
                $result = $feedIo->read($url);

                $rssRaw = file_get_contents($url);
                $rssXml = simplexml_load_string($rssRaw);

                //As imagens e mídias
                $enclosures = [];

                foreach ($rssXml->channel->item as $rssItem) {
                    $link = (string) $rssItem->link;
                    $enclosure = $rssItem->enclosure ? (string) $rssItem->enclosure['url'] : null;

                    if ($link && $enclosure) {
                        $enclosures[$link] = $enclosure;
                    }
                }

                $limit = 20;
                $count = 0;

                foreach ($result->getFeed() as $item) {
                    if($count++ >= $limit) break;

                    $link = $item->getLink();
                    $image = $enclosures[$link] ?? null;

                    $noticias[$name][] = [
                        'title' => $item->getTitle(),
                        'link' => $link,
                        'date' => $item->getLastModified()?->format('d/m/Y H:i'),
                        'description' => $item->getContent(),
                        'image' => $image,
                    ];
                }
            }

            return $noticias;
        });


        return view('/noticias/painel', ['noticias' => $noticias]);
    }

    public function destaques() {
        $noticias = Cache::get('noticias_feed', []);
        $destaques = array_slice($noticias['guiame'] ?? [], 0, 9);

        dd($destaques);

        return view('/noticias/includes/destaques', compact('destaques'));
    }
    
    
  public function podcasts()
{

    $podcasts = Cache::remember('podcasts_feed', 1800, function () {

        $canais = [
            'btcast' => 'https://bibotalk.com/feed/', 
            'cafecf' => 'https://feeds.libsyn.com/466317/rss',
        ];

        $podcasts = [];

        foreach ($canais as $name => $feed) {
            $httpClient = new Client(new GuzzleAdapter(new GuzzleClient()));
            $logger = new NullLogger();
            $feedIo = new FeedIo($httpClient, $logger);

            $url = $feed;
            $rssRaw = @file_get_contents($url);
            $rssXml = $rssRaw ? @simplexml_load_string($rssRaw) : null;

            $enclosures = [];

            if ($rssXml && $rssXml->channel && $rssXml->channel->item) {
                foreach ($rssXml->channel->item as $rssItem) {
                    $link = (string) $rssItem->link;
                    $enclosure = $rssItem->enclosure ? (string) $rssItem->enclosure['url'] : null;
                    if ($link && $enclosure) {
                        $enclosures[$link] = $enclosure;
                    }
                }
            }

            try {
                $result = $feedIo->read($url);
            } catch (\Exception $e) {
                return [];
            }

            $limit = 6;
            $count = 0;

            foreach ($result->getFeed() as $item) {
                if ($count++ >= $limit) break;

                $link = $item->getLink();
                $audio = $enclosures[$link] ?? null;

                $podcasts[$name][] = [
                    'title' => $item->getTitle(),
                    'link' => $link,
                    'date' => $item->getLastModified()?->format('d/m/Y H:i'),
                    'description' => $item->getContent(),
                    'audio' => $audio,
                ];
            }
        }

        return $podcasts;
    });

    return view('/podcasts/painel', ['podcasts' => $podcasts]);
}
    
}
?>