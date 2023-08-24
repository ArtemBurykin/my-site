<?php

namespace App\Tests\Controller;

use App\Entity\Blog\Category;
use App\Entity\Blog\Post;
use App\Tests\Traits\DependenciesTrait;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class OurCasesPageControllerTest extends WebTestCase
{
    use DependenciesTrait;

    public function testSuccessful()
    {
        $client = static::createClient();

        $em = $this->getEntityManager();

        $casesCategory = new Category();
        $casesCategory->setTitle('Our cases')
            ->setSeoUrl('our-cases')
            ->setMetaTitle('Cases meta title')
            ->setMetaDescription('Meta description')
            ->setOgTitle('Og title')
            ->setOgImage('og-image.webp')
            ->setOgDescription('Og description');
        $em->persist($casesCategory);

        $otherCategory = new Category();
        $otherCategory->setTitle('title')
            ->setSeoUrl('other');
        $em->persist($otherCategory);

        $post1 = new Post();
        $post1->setSeoUrl('post-1')
            ->setTitle('post 1')
            ->setCreatedAt(new DateTimeImmutable('-1 day'))
            ->setDescription('post 1 desc')
            ->setCategory($casesCategory)
            ->setContent('[]');
        $em->persist($post1);

        $post2 = new Post();
        $post2->setSeoUrl('post-2')
            ->setTitle('post 2')
            ->setDescription('post 2 desc')
            ->setCategory($otherCategory)
            ->setContent('[]');
        $em->persist($post2);

        $post3 = new Post();
        $post3->setSeoUrl('post-3')
            ->setTitle('post 3')
            ->setDescription('post 3 desc')
            ->setCreatedAt(new DateTimeImmutable('-1 hour'))
            ->setCategory($casesCategory)
            ->setContent('[]');
        $em->persist($post3);

        $em->flush();
        $em->clear();

        $crawler = $client->request(Request::METHOD_GET, '/our-cases');

        $this->assertResponseIsSuccessful();

        $this->assertEquals('Our cases', $crawler->filter('h1')->text());

        $postTitles = $crawler->filter('h2');
        $this->assertCount(2, $postTitles);
        $this->assertEquals('post 3', $postTitles->eq(0)->text());
        $this->assertEquals('post 1', $postTitles->eq(1)->text());

        $this->assertEquals('Cases meta title', $crawler->filter('title')->text());

        $this->assertEquals(
            'Meta description',
            $crawler->filter('meta[name="description"]')->attr('content')
        );

        $this->assertEquals(
            'Og title',
            $crawler->filter('meta[property="og:title"]')->attr('content')
        );

        $this->assertEquals(
            'Og description',
            $crawler->filter('meta[property="og:description"]')->attr('content')
        );

        // @see services.yaml:when@test:host
        $this->assertEquals(
            'https://host.example/uploads/category/og-image.webp',
            $crawler->filter('meta[property="og:image"]')->attr('content')
        );
    }
}
