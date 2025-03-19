<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function acceuil(): Response
    {
        return $this->render('Accueil/accueil.html.twig', [ ]);
    }
}