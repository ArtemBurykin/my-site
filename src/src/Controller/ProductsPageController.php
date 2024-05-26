<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app_products_page')]
class ProductsPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/products_page/products_page.html.twig', []);
    }
}