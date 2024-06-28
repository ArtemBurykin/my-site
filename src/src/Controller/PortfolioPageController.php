<?php

namespace App\Controller;

use App\Repository\Blog\CategoryRepository;
use App\Repository\Blog\PostListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio', name: 'app_portfolio_page')]
class PortfolioPageController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        #[Autowire('%portfolioCategory%')]
        private readonly string $categorySlug,
        private readonly PostListRepository $postRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $portfolioCategory = $this->categoryRepository->findOneBySeoUrl($this->categorySlug);
        $cases = $this->postRepository->findByCategory($portfolioCategory);

        return $this->render(
            'site/portfolio_page/portfolio_page.html.twig',
            ['portfolioCategory' => $portfolioCategory, 'cases' => $cases]
        );
    }
}
