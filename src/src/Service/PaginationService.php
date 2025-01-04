<?php

namespace App\Service;

use App\DTO\Pagination;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Services allows to perform actions connected with pagination. For example, apply the limit and offset
 * to the query builder. Return data that needed for the pagination widget.
 */
final class PaginationService
{
    public function __construct(
        #[Autowire('%postsPerPage%')]
        private readonly int $postsPerPage,
    ) {
    }

    public function addOffsetLimitToQueryBuilderForPage(QueryBuilder $qb, int $page): void
    {
        $offset = ($page - 1) * $this->postsPerPage;

        $qb->setMaxResults($this->postsPerPage)
            ->setFirstResult($offset);
    }

    public function getPaginationDataForPage(int $page, int $totalItemsCount): Pagination
    {
        $pagesCount = 1;
        if ($totalItemsCount > 0) {
            $pagesCount = (int) ceil($totalItemsCount / $this->postsPerPage);
        }

        $startPage = 1;

        $prevPage = null;
        if ($page !== $startPage) {
            $prevPage = $page - 1;
        }

        $nextPage = $page + 1;
        if ($page === $pagesCount) {
            $nextPage = null;
        }

        return new Pagination($pagesCount, $prevPage, $nextPage);
    }
}
