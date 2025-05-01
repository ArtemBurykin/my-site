<?php

namespace App\Twig;

use DateTimeImmutable;
use Symfony\Component\Clock\ClockAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class YearsSinceExtension extends AbstractExtension
{
    use ClockAwareTrait;

    public function getFilters(): array
    {
        return [
            new TwigFilter('years_since', [$this, 'calculateYearSince']),
        ];
    }

    /**
     * @param string $date in format Y-m-d
     */
    public function calculateYearSince(string $date): int
    {
        $d = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        return $this->now()->diff($d)->y;
    }
}
