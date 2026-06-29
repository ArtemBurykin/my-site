<?php

namespace App\Controller;

use App\Repository\Blog\PostListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cases', name: 'app_cases_page')]
class CasesPageController extends AbstractController
{
    public function __construct(
        private readonly PostListRepository $postListRepository,
        #[Autowire('%portfolioCategory%')]
        private readonly string $casesCategorySlug,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $posts = $this->postListRepository->findByCategory($this->casesCategorySlug);

        return $this->render('site/cases_page/cases_page.html.twig', [
            'posts' => $posts,
        ]);
    }
}
