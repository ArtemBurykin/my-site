<?php

namespace App\Repository\Blog;

use App\DTO\PostListItem;
use App\Entity\Blog\Post;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class PostListRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PaginationService $paginationService,
    ) {
    }

    /**
     * @return PostListItem[]
     */
    public function findByCategory(string $categorySlug): array
    {
        $qb = $this->createBaseQueryBuilder();

        $qb
            ->where('c.seoUrl = :slug')
            ->leftJoin('p.category', 'c')
            ->setParameter('slug', $categorySlug);

        $data = $qb->getQuery()->getResult();

        return $this->toPostListItems($data);
    }

    /**
     * @return PostListItem[]
     */
    public function findExcludingCategoryOnPage(string $categoryToExcludeSlug, int $page): array
    {
        $qb = $this->createBaseQueryBuilder();

        $qb->leftJoin('p.category', 'c')
            ->where('c.seoUrl <> :category')
            ->orWhere('p.category is null')
            ->setParameter('category', $categoryToExcludeSlug);

        $this->paginationService->addOffsetLimitToQueryBuilderForPage($qb, $page);

        $data = $qb->getQuery()->getResult();

        return $this->toPostListItems($data);
    }

    public function getCountOfPostsExcludingCategory(string $categoryToExcludeSlug): int
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('count(p.id)')
            ->from(Post::class, 'p')
            ->leftJoin('p.category', 'c')
            ->where('c.seoUrl <> :category')
            ->orWhere('p.category is null')
            ->setParameter('category', $categoryToExcludeSlug);

        return $qb->getQuery()->getSingleScalarResult();
    }

    private function createBaseQueryBuilder(): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('p.id, p.seoUrl, p.title, p.description, p.mainImage, p.createdAt')
            ->from(Post::class, 'p')
            ->orderBy('p.createdAt', 'DESC');

        return $qb;
    }

    /**
     * @return PostListItem[]
     */
    private function toPostListItems(array $data): array
    {
        return array_map(
            fn (array $item) => new PostListItem(
                $item['id'],
                $item['seoUrl'],
                $item['title'],
                $item['description'],
                $item['mainImage'],
                $item['createdAt'],
            ),
            $data
        );
    }
}
