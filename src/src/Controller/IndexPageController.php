<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_index_page')]
class IndexPageController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $version = $request->get('v', '1');

        if ($version === '1') {
            return $this->render('site/index_page/index_page.html.twig');
        } else {
            return $this->render('site/index_page/index_page_2.html.twig');
        }
    }
}
