<?php

namespace App\Controller;

use App\Repository\Blog\PostListRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'app_blog_page')]
class BlogPageController extends AbstractController
{
    public function __construct(
        private readonly PostListRepository $postListRepository,
        private readonly PaginationService $paginationService,
        #[Autowire('%portfolioCategory%')]
        private readonly string $casesCategorySlug,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $posts = $this->postListRepository->findExcludingCategoryOnPage($this->casesCategorySlug, $page);

        $postsCount = $this->postListRepository->getCountOfPostsExcludingCategory($this->casesCategorySlug);
        $pagination = $this->paginationService->getPaginationDataForPage($page, $postsCount);

        return $this->render('site/blog_page/blog_page.html.twig', [
            'posts' => $posts,
            'pagination' => $pagination,
            'page' => $page,
        ]);
    }
}
