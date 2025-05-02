<?php

namespace App\Tests\Editorjs\BlockParsers;

use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CodeParserTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testSuccessfulWithLanguage()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "code",
                    "data": {
                        "code": "hello world",
                        "language": "php"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);

        $crawler = new Crawler($parsedHTML);
        $c = $crawler->filter('code.language-php');
        $this->assertCount(1, $c);
        $this->assertEquals('hello world', $c->innerText());
    }

    public function testSuccessfulWithoutLanguage()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "code",
                    "data": {
                        "code": "hello world"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);

        $crawler = new Crawler($parsedHTML);
        $c = $crawler->filter('code.language-js');
        $this->assertCount(1, $c);
        $this->assertEquals('hello world', $c->innerText());
    }
}
