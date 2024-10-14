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
            <div class="content__img-container preview-trigger"
                data-trigger-popup-id="image-popup"
                data-image="$src"
            >
                <img class="content__img" src="$src" alt="$alt" />
            </div>
            END;
    }
}
