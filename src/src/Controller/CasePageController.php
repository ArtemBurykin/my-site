<?php

namespace App\Controller;

use App\Repository\Blog\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/our-cases/{slug}', name: 'app_case_page')]
class CasePageController extends AbstractController
{
    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function __invoke(string $slug): Response
    {
        $case = $this->postRepository->findOneBySeoUrl($slug);

        if (!$case) {
            throw new NotFoundHttpException('The case not found');
        }

        return $this->render('site/case_page/case_page.html.twig', ['case' => $case]);
    }
}
