<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class ListParser implements BlockParser
{
    public static function getBlockType(): string
    {
        return 'list';
    }

    public function parse(stdClass $blockData): string
    {
        $listTag = match ($blockData->style) {
            'ordered' => 'ol',
            'unordered' => 'ul',
        };

        $listItems = implode(
            '',
            array_map(
                fn (string $item) => "<li class='content__li'>$item</li>",
                $blockData->items
            )
        );

        return <<<"END"
                <$listTag class="content__list">$listItems</$listTag>
            END;
    }
}
