<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private ClientInterface $httpClient;
    private Crawler $crawler;
    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): ?array
    {
        $response = $this->httpClient->request('GET', $url);
        $html = $response->getBody();
        $this->crawler->addHtmlContent($html);
        $elementosCursos = $this->crawler->filter('p.formacao-passo-nome');
        foreach ($elementosCursos as $elemento) {
            $TipoNome = explode("\n", $elemento->textContent);
            if ($TipoNome[0] == 'Curso') {
                $cursos[] = $TipoNome[1];
            }
        }

        return $cursos;
    }
}
