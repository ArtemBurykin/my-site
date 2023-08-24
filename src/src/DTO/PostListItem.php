<?php

namespace App\DTO;

final class PostListItem
{
    public function __construct(
        public readonly int $id,
        public readonly string $seoUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $mainImage,
    ) {
    }
}
