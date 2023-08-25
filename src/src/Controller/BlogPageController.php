<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'app_blog_page')]
class BlogPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/blog_page/blog_page.html.twig');
    }
}
