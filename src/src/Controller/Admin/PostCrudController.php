<?php

namespace App\Controller\Admin;

use App\EasyAdmin\Field\EditorJSField;
use App\Entity\Blog\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        return $crud
            ->addFormTheme('admin/form/editorjs_widget.html.twig')
            ->renderContentMaximized();
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Main information');
        yield TextField::new('title')->setRequired(true);
        yield TextField::new('seoUrl')->setRequired(true);
        yield DateTimeField::new('createdAt')->setRequired(false);
        yield AssociationField::new('category')->setRequired(false);
        yield TextareaField::new('description')->hideOnIndex();

        yield ImageField::new('mainImage')
            ->hideOnIndex()
            ->setUploadDir('public/uploads/post')
            ->setBasePath('uploads/post');

        yield EditorJSField::new('content')->hideOnIndex();

        yield FormField::addTab('Meta information');
        yield TextField::new('metaTitle')->hideOnIndex();
        yield TextareaField::new('metaDescription')->hideOnIndex();

        yield FormField::addTab('Open graph information');
        yield TextField::new('ogTitle')->hideOnIndex();
        yield TextareaField::new('ogDescription')->hideOnIndex();
        yield ImageField::new('ogImage')
            ->hideOnIndex()
            ->setUploadDir('public/uploads/post')
            ->setBasePath('uploads/post');
    }
}
