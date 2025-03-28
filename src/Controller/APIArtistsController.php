<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ArtisteRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class APIArtistsController extends AbstractController
{
    #[Route('/api/artists', name: 'api_get_artists', methods: ['GET'])]
    public function getArtists(ArtisteRepository $artistRepository, SerializerInterface $serializer): JsonResponse
    {
        $artists = $artistRepository->findAll();
        $data = array_map(function ($artist) {
            return [
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'description' => $artist->getDescription(),
                'image' => $artist->getImage(),
                'events' => array_map(function ($event) {
                return [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'date' => $event->getDate()->format('Y-m-d H:i:s'),
                ];
            }, $artist->getEvents()->toArray()),
   
            ];
        }, $artists);
        return $this->json($data);

    }
    #[Route('/api/events', name : 'api_get_events', methods: ['GET'])]
    public function getEvents(EventRepository $eventRepository,SerializerInterface $serializer): JsonResponse
    {
        $events = $eventRepository->findAll();
        $data = array_map(function ($event) {
            return [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'date' => $event->getDate()->format('Y-m-d H:i:s'),
                'artist' => [
                    'id' => $event->getArtist()->getId(),
                    'name' => $event->getArtist()->getName(),
                ],
                'user' => [
                    'id' => $event->getUser()->getId(),
                    'username' => $event->getUser()->getName(),
                ],
            ];
        }, $events);
        return $this->json($data);
    }

    #[Route('/api/artists/{id}', name: 'api_get_artist', methods: ['GET'])]
    public function getArtist(int $id ,ArtisteRepository $artistRepository): JsonResponse
    {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw new NotFoundHttpException('Artist not found');
        }

        return $this->json([
            'id' => $artist->getId(),
            'name' => $artist->getName(),
            'description' => $artist->getDescription(),
            'image' => $artist->getImage(),
            'events' => array_map(function ($event) {
                return [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'date' => $event->getDate()->format('Y-m-d H:i:s'),
                ];
            }, $artist->getEvents()->toArray()),
        ]);
    }
    #[Route('/api/events/{id}', name : 'api_get_event', methods: ['GET'])]
    public function getEvent(int $id ,EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw new NotFoundHttpException('Artist not found');
        }

        return $this->json([
            'id' => $event->getId(),
            'name' => $event->getName(),
            'date' => $event->getDate()->format('Y-m-d H:i:s'),
            'artist' => [
                    'id' => $event->getArtist()->getId(),
                    'name' => $event->getArtist()->getName(),
                ],
            'user' => [
                    'id' => $event->getUser()->getId(),
                    'username' => $event->getUser()->getName(),
                ],
        ]);
    }
}
