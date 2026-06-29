<?php

namespace App\Controller;

use App\Repository\Blog\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cases/{slug}', name: 'app_case_page')]
class CasePageController extends AbstractController
{
    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function __invoke(string $slug): Response
    {
        $post = $this->postRepository->findOneBySeoUrl($slug);

        if (!$post) {
            throw new NotFoundHttpException('The post not found');
        }

        return $this->render('site/case_page/case_page.html.twig', ['post' => $post]);
    }
}
