<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogPageController extends AbstractController
{
    #[Route('/blog', name: 'app_blog_page')]
    public function index(): Response
    {
        return $this->render('site/blog_page/blog_page.html.twig');
    }
}
