<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactsPageController extends AbstractController
{
    #[Route('/contacts', name: 'app_contacts_page')]
    public function index(): Response
    {
        return $this->render('site/contacts_page/contacts_page.html.twig');
    }
}
