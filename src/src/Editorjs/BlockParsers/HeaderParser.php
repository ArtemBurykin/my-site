<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class HeaderParser implements BlockParser
{
    public static function getBlockType(): string
    {
        return 'header';
    }

    public function parse(stdClass $blockData): string
    {
        // Заголовком по умолчанию ставится H2 т.к. это наиболее ожидаемый результат.
        // По правилам семантики заголовок H1 на странице должнен быть только один.
        $level = $blockData->level ?: 2;
        $headerTag = "h$level";
        $classes = 'content__header content__header--h'.$level;

        return <<<"END"
                <$headerTag class="$classes">$blockData->text</$headerTag>
            END;
    }
}
