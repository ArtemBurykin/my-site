<?php

namespace App\Tests\Editorjs\BlockParsers;

use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ParagraphParserTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testSuccessful()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "paragraph",
                    "data": {
                        "text": "some text"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);

        $crawler = new Crawler($parsedHTML);
        $p = $crawler->filter('p.content__simple-text');
        $this->assertCount(1, $p);
        $this->assertEquals('some text', $p->innerText());
    }
}
