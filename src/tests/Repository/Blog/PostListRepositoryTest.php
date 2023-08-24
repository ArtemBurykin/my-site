<?php

namespace App\Tests\Repository\Blog;

use App\DTO\PostListItem;
use App\Entity\Blog\Category;
use App\Entity\Blog\Post;
use App\Repository\Blog\PostListRepository;
use App\Tests\Traits\DependenciesTrait;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostListRepositoryTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testFindByCategory()
    {
        $em = $this->getEntityManager();

        $category1 = new Category();
        $category1->setTitle('Our cases')
            ->setSeoUrl('our-cases');
        $em->persist($category1);

        $category2 = new Category();
        $category2->setTitle('title')
            ->setSeoUrl('other');
        $em->persist($category2);

        $post1 = new Post();
        $post1->setSeoUrl('post-1')
            ->setTitle('post 1')
            ->setCreatedAt(new DateTimeImmutable('-1 day'))
            ->setDescription('post 1 desc')
            ->setCategory($category1)
            ->setMainImage('image.png')
            ->setContent('[]');
        $em->persist($post1);

        $post2 = new Post();
        $post2->setSeoUrl('post-2')
            ->setTitle('post 2')
            ->setDescription('post 2 desc')
            ->setCategory($category2)
            ->setContent('[]');
        $em->persist($post2);

        $post3 = new Post();
        $post3->setSeoUrl('post-3')
            ->setTitle('post 3')
            ->setDescription('post 3 desc')
            ->setCreatedAt(new DateTimeImmutable('-1 hour'))
            ->setCategory($category1)
            ->setContent('[]');
        $em->persist($post3);

        $em->flush();
        $em->clear();

        /** @var PostListRepository $rep */
        $rep = static::getContainer()->get(PostListRepository::class);

        $posts = $rep->findByCategory($category1);

        $this->assertCount(2, $posts);

        $this->assertEquals(
            new PostListItem(
                $post3->getId(),
                $post3->getSeoUrl(),
                $post3->getTitle(),
                $post3->getDescription(),
                $post3->getMainImage()
            ),
            $posts[0]
        );

        $this->assertEquals(
            new PostListItem(
                $post1->getId(),
                $post1->getSeoUrl(),
                $post1->getTitle(),
                $post1->getDescription(),
                $post1->getMainImage()
            ),
            $posts[1]
        );
    }
}
