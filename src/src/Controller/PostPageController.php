<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog/{slug}', name: 'app_post_page')]
class PostPageController extends AbstractController
{
    public function __invoke(string $slug): Response
    {
        return $this->render('site/post_page/post_page.html.twig', ['slug' => $slug]);
    }
}
