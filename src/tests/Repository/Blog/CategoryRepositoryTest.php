<?php

namespace App\Tests\Repository\Blog;

use App\Entity\Blog\Category;
use App\Repository\Blog\CategoryRepository;
use App\Tests\Traits\DependenciesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testFindOneBySeoUrl()
    {
        $category = new Category();
        $category->setSeoUrl('slug')
            ->setTitle('Title');

        $em = $this->getEntityManager();

        $em->persist($category);
        $em->flush();
        $em->clear();

        /** @var CategoryRepository $rep */
        $rep = static::getContainer()->get(CategoryRepository::class);

        $categoryFound = $rep->findOneBySeoUrl('slug');
        $this->assertNotNull($categoryFound);
        $this->assertEquals($category->getId(), $categoryFound->getId());

        $this->assertNull($rep->findOneBySeoUrl('other'));
    }
}
