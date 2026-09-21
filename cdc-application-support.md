# TP Symfony - Application de Base pour Évaluation

## Système de Gestion d'Événements Étudiants "EventCampus"

## Contexte professionnel

Vous êtes développeur web junior dans une startup spécialisée dans les solutions numériques pour l'enseignement supérieur. Le BDE (Bureau Des Étudiants) de votre école vous confie la création d'une plateforme web pour centraliser et promouvoir les événements étudiants du campus.

Cette application doit permettre de :

- Consulter la liste de tous les événements à venir
- Afficher les détails complets de chaque événement
- Filtrer les événements par catégorie (culturel, sportif, associatif, festif)
- Proposer une API pour une future application mobile
- Offrir une interface moderne et responsive

## Objectifs pédagogiques

Cette application servira de base d'évaluation et doit démontrer votre maîtrise de :

- Architecture MVC avec Symfony
- Contrôleurs et système de routage
- Templates Twig avec héritage et composants
- Intégration d'un framework CSS moderne
- Gestion des erreurs et expérience utilisateur

## Spécifications techniques

### Phase 1 : Structure et Configuration (Attendu : 2-3h)

**Installation et initialisation :**

```bash
symfony new eventcampus --webapp
cd eventcampus
```

**Contrôleurs requis :**

- `AccueilController` : page d'accueil avec statistiques générales
- `EvenementController` : gestion complète des événements
- `ApiController` : endpoints JSON pour application mobile

### Phase 2 : Données de Test (Attendu : 1h)

Créez un jeu de données de test comprenant au minimum 8 événements variés :

```php
$evenements = [
    1 => [
        'id' => 1,
        'titre' => 'Soirée Étudiante Halloween',
        'description' => 'Grande soirée costumée pour célébrer Halloween au campus !',
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
    // ... 7 autres événements avec catégories variées
];
```

**Catégories requises :** `culturel`, `sportif`, `associatif`, `festif`

### Phase 3 : Routage et Contrôleurs (Attendu : 3-4h)

**Routes obligatoires :**

- `/` - Accueil avec aperçu des prochains événements
- `/evenements` - Liste complète des événements
- `/evenements/categorie/{categorie}` - Filtrage par catégorie
- `/evenements/{id}` - Détail d'un événement
- `/statistiques` - Page de statistiques des événements
- `/api/evenements` - API JSON de tous les événements
- `/api/evenements/{id}` - API JSON d'un événement spécifique

### Phase 4 : Templates et Interface (Attendu : 4-5h)

**Structure de templates requise :**

```
templates/
├── base.html.twig
├── layout/
│   └── main.html.twig
├── components/
│   ├── navigation.html.twig
│   ├── evenement_card.html.twig
│   └── footer.html.twig
├── home/
│   └── index.html.twig
├── evenement/
│   ├── index.html.twig
│   ├── show.html.twig
│   └── categorie.html.twig
└── statistiques/
    └── index.html.twig
```

**Exigences d'interface :**

- Framework CSS moderne (Bootstrap ou Tailwind)
- Navigation responsive avec menu déroulant pour les catégories
- Cards d'événements avec image, titre, date, lieu
- Page de détail complète avec toutes les informations
- Filtres visuels par catégorie
- Messages flash pour l'expérience utilisateur

### Phase 5 : Fonctionnalités Avancées (Attendu : 2-3h)

- Gestion d'erreurs 404 personnalisées
- Calcul dynamique des places restantes
- Affichage conditionnel selon le statut (ouvert/complet/annulé)
- API JSON avec gestion d'erreurs appropriée
- Page de statistiques avec répartition par catégories


---
