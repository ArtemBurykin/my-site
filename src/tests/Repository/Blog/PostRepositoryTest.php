<?php

namespace App\Tests\Repository\Blog;

use App\Entity\Blog\Post;
use App\Repository\Blog\PostRepository;
use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testFindOneBySeoUrl()
    {
        $em = $this->getEntityManager();

        $post = new Post();
        $post->setSeoUrl('post-1')
            ->setTitle('post 1')
            ->setDescription('post 1')
            ->setContent('[]');
        $em->persist($post);

        $post2 = new Post();
        $post2->setSeoUrl('post-2')
            ->setTitle('post 2')
            ->setDescription('post 1')
            ->setContent('[]');
        $em->persist($post2);

        $em->flush();
        $em->clear();

        /** @var PostRepository $rep */
        $rep = static::getContainer()->get(PostRepository::class);

        $postFound = $rep->findOneBySeoUrl('post-1');
        $this->assertSame($post->getId(), $postFound->getId());

        $this->assertNull($rep->findOneBySeoUrl('other'));
    }
}
