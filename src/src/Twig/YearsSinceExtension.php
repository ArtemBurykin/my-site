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
        return array(
            new TwigFilter('years_since', array($this, 'calculateYearSince')),
        );
    }

    /**
     * @param string $date in format Y-m-d
     *
     * @return int
     */
    public function calculateYearSince(string $date): int
    {
        $d = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        return $this->now()->diff($d)->y;
    }
}