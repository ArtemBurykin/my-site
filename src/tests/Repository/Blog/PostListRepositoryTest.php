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

    public function testFindExcludingCategoryOnPage()
    {
        $em = $this->getEntityManager();

        $category1 = new Category();
        $category1->setTitle('category 1')
            ->setSeoUrl('cat-1');
        $em->persist($category1);

        $category2 = new Category();
        $category2->setTitle('cat-2')
            ->setSeoUrl('cat-2');
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

        $post4 = new Post();
        $post4->setSeoUrl('post-4')
            ->setTitle('post 4')
            ->setDescription('post 4 desc')
            ->setCreatedAt(new DateTimeImmutable('-3 hours'))
            ->setContent('[]');
        $em->persist($post4);

        $post5 = new Post();
        $post5->setSeoUrl('post-5')
            ->setTitle('post 5')
            ->setDescription('post 4 desc')
            ->setCreatedAt(new DateTimeImmutable('-5 hours'))
            ->setCategory($category1)
            ->setContent('[]');
        $em->persist($post5);

        $em->flush();
        $em->clear();

        /** @var PostListRepository $rep */
        $rep = static::getContainer()->get(PostListRepository::class);

        $posts1Page = $rep->findExcludingCategoryOnPage($category2->getSeoUrl(), 1);

        /* @see services.yaml::when@test::postsPerPage */
        $this->assertCount(2, $posts1Page);

        $this->assertEquals(
            new PostListItem(
                $post3->getId(),
                $post3->getSeoUrl(),
                $post3->getTitle(),
                $post3->getDescription(),
                $post3->getMainImage()
            ),
            $posts1Page[0]
        );

        $this->assertEquals(
            new PostListItem(
                $post4->getId(),
                $post4->getSeoUrl(),
                $post4->getTitle(),
                $post4->getDescription(),
                $post4->getMainImage()
            ),
            $posts1Page[1]
        );

        $posts2Page = $rep->findExcludingCategoryOnPage($category2->getSeoUrl(), 2);

        /* @see services.yaml::when@test::postsPerPage */
        $this->assertCount(2, $posts1Page);

        $this->assertEquals(
            new PostListItem(
                $post5->getId(),
                $post5->getSeoUrl(),
                $post5->getTitle(),
                $post5->getDescription(),
                $post5->getMainImage()
            ),
            $posts2Page[0]
        );

        $this->assertEquals(
            new PostListItem(
                $post1->getId(),
                $post1->getSeoUrl(),
                $post1->getTitle(),
                $post1->getDescription(),
                $post1->getMainImage()
            ),
            $posts2Page[1]
        );
    }

    public function testGetCountOfPostsExcludingCategory()
    {
        $em = $this->getEntityManager();

        $category1 = new Category();
        $category1->setTitle('category 1')
            ->setSeoUrl('cat-1');
        $em->persist($category1);

        $category2 = new Category();
        $category2->setTitle('cat-2')
            ->setSeoUrl('cat-2');
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
            ->setContent('[]');
        $em->persist($post3);

        $em->flush();
        $em->clear();

        /** @var PostListRepository $rep */
        $rep = static::getContainer()->get(PostListRepository::class);

        $this->assertSame(2, $rep->getCountOfPostsExcludingCategory($category2->getSeoUrl()));
    }
}
