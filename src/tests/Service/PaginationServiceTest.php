<?php

namespace App\Tests\Service;

use App\DTO\Pagination;
use App\Service\PaginationService;
use PHPUnit\Framework\TestCase;

class PaginationServiceTest extends TestCase
{
    public function testGetPaginationDataForPage()
    {
        $paginator = new PaginationService(postsPerPage: 2);

        $this->assertEquals(
            new Pagination(
                2,
                null,
                2
            ),
            $paginator->getPaginationDataForPage(1, 4)
        );

        $this->assertEquals(
            new Pagination(
                3,
                1,
                3,
            ),
            $paginator->getPaginationDataForPage(2, 6)
        );

        $this->assertEquals(
            new Pagination(
                3,
                2,
                null,
            ),
            $paginator->getPaginationDataForPage(3, 6)
        );

        $this->assertEquals(
            new Pagination(
                1,
                null,
                null,
            ),
            $paginator->getPaginationDataForPage(1, 0)
        );

        $this->assertEquals(
            new Pagination(
                2,
                null,
                2,
            ),
            $paginator->getPaginationDataForPage(1, 3)
        );
    }
}
