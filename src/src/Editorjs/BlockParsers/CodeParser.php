<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class CodeParser implements BlockParser
{
    public static function getBlockType(): string
    {
        return 'code';
    }

    public function parse(stdClass $blockData): string
    {
        $code = $blockData->code;
        $langClass = isset($blockData->language) ? 'language-'.$blockData->language : 'language-js';

        return <<< "END"
            <pre><code class="$langClass">$code</code></pre>
            END;
    }
}
