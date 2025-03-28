<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Event;
use App\Entity\User;
use App\Form\ArtisteType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ArtisteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class EventsController extends AbstractController{

    #[Route('/events', name: 'events')]
public function renderEvents(
    EventRepository $eventRepository, 
    ArtisteRepository $artisteRepository, 
    Request $request, 
    EntityManagerInterface $entityManager
): Response {
    $events = $eventRepository->findAll();
    $artists = $artisteRepository->findAll();

    // Création du formulaire
    $event = new Event();
    $event->setUser($this->getUser());
    $form = $this->createForm(EventType::class, $event);
    $form->handleRequest($request);

    // Traitement du formulaire
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->redirectToRoute('events'); // Recharge la page pour voir la mise à jour
    }

    return $this->render('events/index.html.twig', [
        'events' => $events,
        'artists' => $artists,
        'form' => $form->createView(), 
    ]);
}


#[Route('/events/{id}', name: 'event', methods: ['GET', 'POST'])]
public function renderEvent(
    int $id,
    EventRepository $eventRepository,
    ArtisteRepository $artisteRepository,
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $event = $eventRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('The event does not exist');
    }

    $artists = $artisteRepository->findAll();
    $form = $this->createForm(EventType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('event', ['id' => $id]);
    }

    return $this->render('events/event.html.twig', [
        'event' => $event,
        'artists' => $artists,
        'form' => $form->createView()
    ]);
}

    #[Route('/events/{id}/delete', name: 'delete_event', methods:['GET', 'POST'])]
    public function deleteEvent(int $id, EventRepository $eventRepository,EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('The event does not exist');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('events');
    }
    
}
