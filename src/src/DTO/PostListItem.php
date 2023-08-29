<?php

namespace App\DTO;

final readonly class PostListItem
{
    public function __construct(
        public int $id,
        public string $seoUrl,
        public string $title,
        public string $description,
        public ?string $mainImage,
    ) {
    }
}
