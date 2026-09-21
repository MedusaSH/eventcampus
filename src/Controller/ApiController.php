<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;

#[Route('/api')]
final class ApiController extends AbstractController
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    #[Route('/evenements', name: 'api_evenements', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $evenements = $this->store->getEvenement();

        return $this->json([
            'success' => true,
            'count' => count($evenements),
            'data' => array_values($evenements),
        ]);
    }

    #[Route('/evenements/{id}', name: 'api_evenements_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $evenement = $this->store->getEvenementById($id);

        if ($evenement === null) {
            return $this->json([
                'success' => false,
                'error' => 'Événement non trouvé',
                'code' => 404,
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $evenement,
        ]);
    }
}
