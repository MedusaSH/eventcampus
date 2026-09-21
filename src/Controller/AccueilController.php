<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;

final class AccueilController extends AbstractController
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $events = $this->store->get3Evenement();
        return $this->render('home/index.html.twig', [
            'events' => $events,
        ]);
    }
}
