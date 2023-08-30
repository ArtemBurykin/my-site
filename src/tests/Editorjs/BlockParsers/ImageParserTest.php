<?php

namespace App\Tests\Editorjs\BlockParsers;

use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ImageParserTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testParseSuccess()
    {
        $extension = $this->getEditorjsExtension();

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "image",
                    "data": {
                        "file": {
                            "url":"/uploads/editorjs/Screenshot-from-2022-07-14-15-57-20-633431860e8b9.png"
                        },
                        "withBorder": false,
                        "stretched": false,
                        "withBackground": false,
                        "caption": "test"
                    }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);

        $image = $crawler->filter('img.content__img');
        $this->assertStringContainsString(
            '/uploads/editorjs/Screenshot-from-2022-07-14-15-57-20-633431860e8b9.png',
            $image->getNode(0)->attributes->getNamedItem('src')->textContent,
        );
        $this->assertStringContainsString(
            'test',
            $image->getNode(0)->attributes->getNamedItem('alt')->textContent,
        );
    }
}
