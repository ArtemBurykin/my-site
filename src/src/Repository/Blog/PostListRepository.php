<?php

namespace App\Repository\Blog;

use App\DTO\PostListItem;
use App\Entity\Blog\Category;
use App\Entity\Blog\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostListRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @return PostListItem[]
     */
    public function findByCategory(Category $category): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p.id, p.seoUrl, p.title, p.description, p.mainImage')
            ->from(Post::class, 'p')
            ->where('p.category = :category')
            ->setParameter('category', $category->getId())
            ->orderBy('p.createdAt', 'DESC');

        $data = $qb->getQuery()->getResult();

        return array_map(
            fn (array $item) => new PostListItem(
                $item['id'],
                $item['seoUrl'],
                $item['title'],
                $item['description'],
                $item['mainImage']
            ),
            $data
        );
    }
}
