<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contacts', name: 'app_contacts_page')]
class ContactsPageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('site/contacts_page/contacts_page.html.twig');
    }
}
