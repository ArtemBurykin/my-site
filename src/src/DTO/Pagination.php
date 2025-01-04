<?php

namespace App\DTO;

final readonly class Pagination
{
    public function __construct(
        public int $totalPages,
        public ?int $prevPage,
        public ?int $nextPage,
    ) {
    }
}
