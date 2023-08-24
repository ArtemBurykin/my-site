<?php

namespace App\Controller;

use App\Repository\Blog\CategoryRepository;
use App\Repository\Blog\PostListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OurCasesPageController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        #[Autowire('%casesCategory%')]
        private readonly string $categorySlug,
        private readonly PostListRepository $postRepository,
    ) {
    }

    #[Route('/our-cases', name: 'app_our_cases_page')]
    public function index(): Response
    {
        $casesCategory = $this->categoryRepository->findOneBySeoUrl($this->categorySlug);
        $cases = $this->postRepository->findByCategory($casesCategory);
        // IMHERE: add the category description to markup

        return $this->render(
            'site/our_cases_page/our_cases_page.html.twig',
            ['casesCategory' => $casesCategory, 'cases' => $cases]
        );
    }
}
