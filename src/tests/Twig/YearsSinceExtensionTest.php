<?php

namespace App\Tests\Twig;

use App\Twig\YearsSinceExtension;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

class YearsSinceExtensionTest extends TestCase
{
    use ClockSensitiveTrait;

    public function testCalculateYearsSince(): void
    {
        $clock = static::mockTime(new DateTimeImmutable('2024-03-02'));

        $ext = new YearsSinceExtension();
        $ext->setClock($clock);

        $this->assertSame(2, $ext->calculateYearSince('2021-08-03'));
        $this->assertSame(3, $ext->calculateYearSince('2021-03-01'));
        $this->assertSame(2, $ext->calculateYearSince('2021-03-02'));
        $this->assertSame(5, $ext->calculateYearSince('2018-12-02'));
    }
}
