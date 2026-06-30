<?php

namespace App\Controller;

use App\Entity\Blog\Post;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog/{slug}', name: 'app_post_page')]
class PostPageController extends AbstractController
{
    public function __invoke(#[MapEntity(mapping: ['slug' => 'seoUrl'])] ?Post $post): Response
    {
        if (!$post) {
            throw new NotFoundHttpException('The post not found');
        }

        return $this->render('site/post_page/post_page.html.twig', ['post' => $post]);
    }
}
