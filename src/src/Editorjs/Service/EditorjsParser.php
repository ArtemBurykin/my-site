<?php

namespace App\Editorjs\Service;

use App\Editorjs\BlockParsers\BlockParser;
use BadMethodCallException;
use stdClass;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Traversable;

/**
 * To parse blocks from Editor js to the HTML code.
 */
class EditorjsParser
{
    private array $parserPlugins;

    /**
     * @param BlockParser[] $parserPlugins коллекция парсеров, получаемая из конфигурации
     */
    public function __construct(
        #[AutowireIterator('app.editorjs_parser_extension', null, 'getBlockType')]
        iterable $parserPlugins
    ) {
        if (!$parserPlugins instanceof Traversable) {
            throw new BadMethodCallException();
        }

        $this->parserPlugins = iterator_to_array($parserPlugins);
    }

    /**
     * Преобразует массив блоков в строку HTML кода.
     */
    public function parse(?stdClass $data): ?string
    {
        if (!$data || 0 === count($data->blocks)) {
            return null;
        }

        $parsedHTML = array_map(
            fn (stdClass $block) => array_key_exists($block->type, $this->parserPlugins)
                ? $this->parserPlugins[$block->type]->parse($block->data)
                : null,
            $data->blocks
        );

        return implode('', $parsedHTML);
    }
}
