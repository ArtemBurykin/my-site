<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_index_page')]
class IndexPageController extends AbstractController
{
    public function __invoke(): Response
    {
        $dates = [
            'startCareer' => '2009-05-20',
            'angular' => '2017-03-01',
            'react' => '2020-02-10',
            'express' => '2020-03-02',
            'symfony' => '2015-10-10',
            'wordpress' => '2010-10-09',
            'clojure' => '2018-09-20',
            'flutter' => '2020-05-02',
        ];

        return $this->render('site/index_page/index_page.html.twig', ['dates' => $dates]);
    }
}
