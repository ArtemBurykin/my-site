<?php

namespace App\Editorjs\Twig;

use App\Editorjs\Service\EditorjsParser;
use stdClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EditorjsTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly EditorjsParser $editorjsParser,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('editorjs_parse', [$this, 'editorjsParse']),
        ];
    }

    public function editorjsParse(string $dataString): ?string
    {
        $data = json_decode($dataString);

        if (!($data instanceof stdClass)) {
            return null;
        }

        return $this->editorjsParser->parse($data);
    }
}
