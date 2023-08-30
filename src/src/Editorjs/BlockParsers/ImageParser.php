<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class ImageParser implements BlockParser
{
    public static function getBlockType(): string
    {
        return 'image';
    }

    public function parse(stdClass $blockData): string
    {
        $src = $blockData->file->url;
        $alt = htmlspecialchars($blockData->caption);

        return <<< "END"
            <div class="content__img-container">
                <img class="content__img" loading="lazy" src="$src" alt="$alt" />
            </div>
            END;
    }
}
