<?php

namespace App\Controller;

use App\Form\ArtisteType;
use App\Entity\Artiste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArtisteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArtistsController extends AbstractController
{
    #[Route('/artists', name: 'artists')]
    public function renderArtists(ArtisteRepository $artisteRepository): Response
    {
        $artists = $artisteRepository->findAll(); 

        return $this->render('artists.html.twig', ['artists'=> $artists]);
    }

    
    #[Route('/artists/add', name: 'add_artist', methods:['POST'])]
    public function addArtist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artist);
        $form->handleRequest($request);
        dd($form);
        $imageFile = $form->get('Image')->getData();
        
        if ($imageFile) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = $imageFile->guessExtension();
        
            if (!in_array($extension, $allowedExtensions)) {
                $this->addFlash('error', 'Format d\'image non supporté.');
                return $this->redirectToRoute('artists');
            }
        
            $newFilename = uniqid().'.'.$extension;
            $imageFile->move($this->getParameter('artist_images_directory'), $newFilename);
            $artist->setImage($newFilename);
        }
            $entityManager->persist($artist);
            $entityManager->flush();
        return $this->redirectToRoute('artists');
    }

    
    #[Route('/artists/{id}', name: 'artist')]
    public function renderArtist(int $id, ArtisteRepository $artisteRepository): Response
    {
        $artist = $artisteRepository->find($id);
        $events = $artist->getEvents();

        if (!$artist) {
            throw $this->createNotFoundException('The artist does not exist');
        }

        return $this->render('artist/artist.html.twig', ['artist' => $artist , 'events' => $events]);
    }
    #[Route('/artists/{id}/delete', name: 'delete_artist', methods:['GET', 'POST'])]
    public function deleteArtist(int $id, ArtisteRepository $artisteRepository,EntityManagerInterface $entityManager): Response
    {
        $artist = $artisteRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('The artist does not exist');
        }

        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->redirectToRoute('artists');
    }

    #[Route('/artists/{id}/edit', name: 'edit_artist', methods:['GET', 'POST'])]
    public function editArtist(int $id, ArtisteRepository $artisteRepository): Response
    {
        $artist = $artisteRepository->find($id);
        $events = $artist->getEvents();

        if (!$artist) {
            throw $this->createNotFoundException('The artist does not exist');
        }

        return $this->render('artist/edit.html.twig', ['artist' => $artist, 'events' => $events]);
    }
    
}