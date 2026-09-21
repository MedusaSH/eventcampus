<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;

final class EvenementController extends AbstractController
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    #[Route('/evenements', name: 'app_evenements')]
    public function index(): Response
    {
        $events = $this->store->getEvenement();

        return $this->render('evenement/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/evenements/categorie/{categorie}', name: 'app_evenements_categorie')]
    public function categorie(string $categorie): Response
    {
        $categoriesValides = ['culturel', 'sportif', 'associatif', 'festif'];

        if (!in_array($categorie, $categoriesValides)) {
            $this->addFlash('warning', 'Catégorie "' . $categorie . '" non reconnue.');
            return $this->redirectToRoute('app_evenements');
        }

        $events = $this->store->getEvenementsByCategorie($categorie);

        return $this->render('evenement/categorie.html.twig', [
            'events' => $events,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/evenements/{id}', name: 'app_evenements_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $event = $this->store->getEvenementById($id);

        if ($event === null) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        return $this->render('evenement/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/statistiques', name: 'app_statistiques')]
    public function statistiques(): Response
    {
        $events = $this->store->getEvenement();

        $nbEvenements = count($events);
        $totalPlaces = 0;
        $totalPlacesOccupees = 0;
        $repartitionCategories = [];

        foreach ($events as $event) {
            $totalPlaces += $event['places_totales'];
            $placesOccupees = $event['places_totales'] - $event['places_disponibles'];
            $totalPlacesOccupees += $placesOccupees;

            $cat = $event['categorie'];
            if (!isset($repartitionCategories[$cat])) {
                $repartitionCategories[$cat] = 0;
            }
            $repartitionCategories[$cat]++;
        }

        $moyennePlaces = $nbEvenements > 0 ? $totalPlaces / $nbEvenements : 0;
        $tauxOccupation = $totalPlaces > 0 ? ($totalPlacesOccupees / $totalPlaces) * 100 : 0;

        return $this->render('statistiques/index.html.twig', [
            'nbEvenements' => $nbEvenements,
            'totalPlaces' => $totalPlaces,
            'totalPlacesOccupees' => $totalPlacesOccupees,
            'moyennePlaces' => round($moyennePlaces, 1),
            'tauxOccupation' => round($tauxOccupation, 1),
            'repartitionCategories' => $repartitionCategories,
        ]);
    }
}
