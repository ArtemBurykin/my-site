<?php

namespace App\Tests\Editorjs\BlockParsers;

use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class HeaderParserTest extends KernelTestCase
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
                    "type": "header",
                    "data": {
                        "level": 2,
                        "text": "some text"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);

        $crawler = new Crawler($parsedHTML);
        $h = $crawler->filter('h2.content__header--h2');
        $this->assertCount(1, $h);
        $this->assertEquals('some text', $h->innerText());
    }

    public function testSuccessfulOtherLevel()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "header",
                    "data": {
                        "level": 3,
                        "text": "some text"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);

        $crawler = new Crawler($parsedHTML);
        $h = $crawler->filter('h3.content__header--h3');
        $this->assertCount(1, $h);
        $this->assertEquals('some text', $h->innerText());
    }
}
