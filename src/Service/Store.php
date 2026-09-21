<?php

namespace App\Service;

class Store
{
    public function get3Evenement(): array
    {
        return array_slice($this->getEvenement(), -3, 3);
    }

    public function getEvenementById(int $id): ?array
    {
        $evenements = $this->getEvenement();
        return $evenements[$id] ?? null;
    }

    public function getEvenementsByCategorie(string $categorie): array
    {
        return array_filter($this->getEvenement(), function ($e) use ($categorie) {
            return $e['categorie'] === $categorie;
        });
    }

    public function getEvenement(): array
    {
        return [
            1 => [
                'id' => 1,
                'titre' => 'Soirée Étudiante Halloween',
                'description' => 'Grande soirée costumée pour célébrer Halloween au campus ! Venez déguisés et participez au concours du meilleur costume avec de nombreux lots à gagner.',
                'date_debut' => '2024-10-31 20:00:00',
                'date_fin' => '2024-11-01 02:00:00',
                'lieu' => 'Amphithéâtre Central',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 8.0,
                'places_disponibles' => 150,
                'places_totales' => 200,
                'image' => 'halloween.jpg',
                'statut' => 'ouvert'
            ],
            2 => [
                'id' => 2,
                'titre' => 'Tournoi de Badminton Inter-Filières',
                'description' => 'Compétition amicale de badminton en double ouverte à tous les étudiants. Niveau débutant à confirmé. Raquettes fournies sur place.',
                'date_debut' => '2024-11-15 14:00:00',
                'date_fin' => '2024-11-15 18:00:00',
                'lieu' => 'Gymnase Universitaire',
                'categorie' => 'sportif',
                'organisateur' => 'BDS Campus',
                'prix' => 0.0,
                'places_disponibles' => 32,
                'places_totales' => 32,
                'image' => 'badminton.jpg',
                'statut' => 'ouvert'
            ],
            3 => [
                'id' => 3,
                'titre' => 'Conférence : L\'IA et l\'Éthique',
                'description' => 'Table ronde animée par des enseignants-chercheurs sur l\'impact sociétal de l\'intelligence artificielle. Questions du public bienvenues.',
                'date_debut' => '2024-11-20 18:30:00',
                'date_fin' => '2024-11-20 20:30:00',
                'lieu' => 'Grand Amphi A',
                'categorie' => 'culturel',
                'organisateur' => 'Département Informatique',
                'prix' => 0.0,
                'places_disponibles' => 45,
                'places_totales' => 120,
                'image' => 'conference_ia.jpg',
                'statut' => 'ouvert'
            ],
            4 => [
                'id' => 4,
                'titre' => 'Collecte Alimentaire Solidaire',
                'description' => 'Mobilisation pour récolter des denrées non périssables au profit des étudiants précaires. Chaque don compte !',
                'date_debut' => '2024-11-25 09:00:00',
                'date_fin' => '2024-11-26 17:00:00',
                'lieu' => 'Hall Principal',
                'categorie' => 'associatif',
                'organisateur' => 'Agora Éco',
                'prix' => 0.0,
                'places_disponibles' => 0,
                'places_totales' => 0,
                'image' => 'collecte.jpg',
                'statut' => 'ouvert'
            ],
            5 => [
                'id' => 5,
                'titre' => 'Gala de Fin de Semestre',
                'description' => 'Grande soirée de gala annuelle avec buffet gastronomique, concert live et DJ set jusqu\'au bout de la nuit.',
                'date_debut' => '2024-12-13 20:30:00',
                'date_fin' => '2024-12-14 04:00:00',
                'lieu' => 'Salle des Fêtes',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 15.0,
                'places_disponibles' => 0,
                'places_totales' => 300,
                'image' => 'gala.jpg',
                'statut' => 'complet'
            ],
            6 => [
                'id' => 6,
                'titre' => 'Exposition Photo : Regards croisés',
                'description' => 'Vernissage et exposition des plus beaux clichés réalisés par le club photo du campus. Thème : la vie étudiante.',
                'date_debut' => '2024-12-02 10:00:00',
                'date_fin' => '2024-12-06 18:00:00',
                'lieu' => 'Galerie de la BU',
                'categorie' => 'culturel',
                'organisateur' => 'Club Photo',
                'prix' => 0.0,
                'places_disponibles' => 80,
                'places_totales' => 80,
                'image' => 'exposition.jpg',
                'statut' => 'ouvert'
            ],
            7 => [
                'id' => 7,
                'titre' => 'Marathon Caritatif 10km',
                'description' => 'Course solidaire dont les bénéfices seront reversés à une association locale. Parcours accessible à tous les niveaux.',
                'date_debut' => '2024-12-08 09:00:00',
                'date_fin' => '2024-12-08 13:00:00',
                'lieu' => 'Parc du Campus',
                'categorie' => 'sportif',
                'organisateur' => 'BDS Campus',
                'prix' => 5.0,
                'places_disponibles' => 87,
                'places_totales' => 150,
                'image' => 'marathon.jpg',
                'statut' => 'ouvert'
            ],
            8 => [
                'id' => 8,
                'titre' => 'Atelier CV et Entretien',
                'description' => 'Session de coaching animée par des professionnels RH. Apportez votre CV pour une relecture personnalisée.',
                'date_debut' => '2024-12-10 14:00:00',
                'date_fin' => '2024-12-10 17:00:00',
                'lieu' => 'Salle B204',
                'categorie' => 'associatif',
                'organisateur' => 'Bureau des Stages',
                'prix' => 0.0,
                'places_disponibles' => 5,
                'places_totales' => 25,
                'image' => 'atelier_cv.jpg',
                'statut' => 'ouvert'
            ],
        ];
    }
}
