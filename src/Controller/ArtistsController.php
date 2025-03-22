<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArtistsController extends AbstractController
{
    #[Route('/artists', name: 'artists')]
    public function renderArtits(): Response
    {
        return $this->render('artists.html.twig', [ ]);
    }
}