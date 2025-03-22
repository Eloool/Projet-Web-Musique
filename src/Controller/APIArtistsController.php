<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class APIArtistsController extends AbstractController
{
    #[Route('/api/hello', methods: ['GET'])]
    #[OA\Response(
        response:200,
        description: "Réponse réussie",
        content: new OA\JsonContent(
            type: "array",
            items : new OA\Items(
                type: "string",
                items: new OA\Items(ref: new Model(type: AlbumDto::class, groups: ['full']))
            )
        ))]
        #[OA\Parameter(
            name: 'order',
            in: 'query',
            description: 'The field used to order rewards',
            schema: new OA\Schema(type: 'string')
        )]
        #[OA\Tag(name: 'rewards')]
    public function hello(): JsonResponse
    {
        return $this->json(['message' => 'Bonjour, API !']);
    }
    #[Route('/test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json(['message' => 'Test']);
    }
}
