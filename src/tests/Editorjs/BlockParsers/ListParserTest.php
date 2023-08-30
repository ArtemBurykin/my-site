<?php

namespace App\Tests\Editorjs\BlockParsers;

use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ListParserTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testEditorjsParseListOrderedSuccess()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "list",
                    "data": {
                        "style" : "ordered",
                        "items" : [
                            "Первый элемент списка",
                            "Второй элемент списка"
                        ]
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);

        $li = $crawler->filter('ol.content__list>li.content__li');
        $this->assertCount(2, $li);
        $this->assertEquals('Первый элемент списка', $li->eq(0)->innerText());
        $this->assertEquals('Второй элемент списка', $li->eq(1)->innerText());
    }

    public function testEditorjsParseListUnorderedSuccess()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "list",
                    "data": {
                        "style" : "unordered",
                        "items" : [
                            "Первый элемент списка",
                            "Второй элемент списка"
                        ]
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);

        $li = $crawler->filter('ul.content__list>li.content__li');
        $this->assertCount(2, $li);
        $this->assertEquals('Первый элемент списка', $li->eq(0)->innerText());
        $this->assertEquals('Второй элемент списка', $li->eq(1)->innerText());
    }

    public function testEditorjsParseListNoItemsSuccess()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "list",
                    "data": {
                        "style" : "unordered",
                        "items" : []
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);

        $li = $crawler->filter('ul.content__list>li.content__li');
        $this->assertCount(0, $li);
    }
}
