<?php

namespace App\Tests\Controller;

use App\Entity\Blog\Post;
use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostPageControllerTest extends WebTestCase
{
    use DependenciesTrait;

    public function testNotFound()
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/blog/other');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSuccessful()
    {
        $client = static::createClient();

        $em = $this->getEntityManager();

        $post = new Post();
        $post->setSeoUrl('post-1')
            ->setTitle('post 1')
            ->setMetaTitle('meta title')
            ->setMetaDescription('meta description')
            ->setOgTitle('og title')
            ->setOgImage('og-image.webp')
            ->setOgDescription('og description')
            ->setDescription('post 1 desc')
            ->setContent('[]');
        $em->persist($post);

        $em->flush();
        $em->clear();

        $crawler = $client->request(Request::METHOD_GET, '/blog/post-1');

        $this->assertResponseIsSuccessful();

        $this->assertSame('post 1', $crawler->filter('h1')->text());

        $this->assertEquals('meta title', $crawler->filter('title')->text());

        $this->assertEquals(
            'meta description',
            $crawler->filter('meta[name="description"]')->attr('content')
        );

        $this->assertEquals(
            'og title',
            $crawler->filter('meta[property="og:title"]')->attr('content')
        );

        $this->assertEquals(
            'og description',
            $crawler->filter('meta[property="og:description"]')->attr('content')
        );

        // @see services.yaml:when@test:host
        $this->assertEquals(
            'https://host.example/uploads/post/og-image.webp',
            $crawler->filter('meta[property="og:image"]')->attr('content')
        );
    }
}
