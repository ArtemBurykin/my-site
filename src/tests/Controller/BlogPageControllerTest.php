<?php

namespace App\Tests\Controller;

use App\Entity\Blog\Category;
use App\Entity\Blog\Post;
use App\Tests\Traits\DependenciesTrait;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class BlogPageControllerTest extends WebTestCase
{
    use DependenciesTrait;

    public function testSuccessful()
    {
        $client = static::createClient();

        $em = $this->getEntityManager();

        $category1 = new Category();
        $category1->setTitle('category 1')
            ->setSeoUrl('category-1');
        $em->persist($category1);

        /** @see services.yaml::casesCategory */
        $categoryToExclude = new Category();
        $categoryToExclude->setTitle('Our cases')
            ->setSeoUrl('our-cases');
        $em->persist($categoryToExclude);

        $post1 = new Post();
        $post1->setSeoUrl('post-1')
            ->setTitle('post 1')
            ->setCreatedAt(new DateTimeImmutable('-1 day'))
            ->setDescription('post 1 desc')
            ->setCategory($category1)
            ->setContent('[]');
        $em->persist($post1);

        $post2 = new Post();
        $post2->setSeoUrl('post-2')
            ->setTitle('post 2')
            ->setDescription('post 2 desc')
            ->setCategory($categoryToExclude)
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
            ->setCreatedAt(new DateTimeImmutable('-2 hours'))
            ->setContent('[]');
        $em->persist($post4);

        $em->flush();
        $em->clear();

        $crawler = $client->request(Request::METHOD_GET, '/blog');

        $this->assertResponseIsSuccessful();

        $this->assertEquals('Our blog', $crawler->filter('h1')->text());

        $postTitles = $crawler->filter('h2');
        $this->assertCount(2, $postTitles);
        $this->assertEquals('post 3', $postTitles->eq(0)->text());
        $this->assertEquals('post 4', $postTitles->eq(1)->text());

        $this->assertStringContainsString('1 of 2', $crawler->text());
    }
}
