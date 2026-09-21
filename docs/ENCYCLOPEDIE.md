# EventCampus — Encyclopédie Complète

> [!success] Document de référence pour l'évaluation BTS SIO SLAM
> Ce document contient **TOUT** ce que tu dois savoir pour comprendre, expliquer et modifier le projet EventCampus.

---

# Table des matières

## Partie 1 — Comprendre Symfony
1. [[#1.1 Architecture MVC]]
2. [[#1.2 Structure des dossiers]]
3. [[#1.3 Le cycle de vie d'une requête]]

## Partie 2 — Les Contrôleurs
4. [[#2.1 Qu'est-ce qu'un contrôleur]]
5. [[#2.2 Les routes avec attributs]]
6. [[#2.3 Injection de dépendances]]
7. [[#2.4 Méthodes utiles d'AbstractController]]

## Partie 3 — Le Service Store
8. [[#3.1 Qu'est-ce qu'un service]]
9. [[#3.2 Structure des données événements]]
10. [[#3.3 Méthodes du Store]]

## Partie 4 — Twig (Templates)
11. [[#4.1 Syntaxe de base]]
12. [[#4.2 Héritage de templates]]
13. [[#4.3 Include et composants]]
14. [[#4.4 Variables avec set]]
15. [[#4.5 Conditions]]
16. [[#4.6 Opérateur ternaire]]
17. [[#4.7 Boucles]]
18. [[#4.8 Filtres]]
19. [[#4.9 Fonctions]]

## Partie 5 — API JSON
20. [[#5.1 Différence HTML vs JSON]]
21. [[#5.2 JsonResponse]]
22. [[#5.3 Gestion des erreurs API]]

## Partie 6 — Bootstrap
23. [[#6.1 Installation avec AssetMapper]]
24. [[#6.2 Composants utilisés]]
25. [[#6.3 Système de grille]]
26. [[#6.4 Classes utilitaires]]

## Partie 7 — Fonctionnalités avancées
27. [[#7.1 Messages flash]]
28. [[#7.2 Page 404 personnalisée]]
29. [[#7.3 Validation des données]]

## Partie 8 — Récapitulatif pour l'évaluation
30. [[#8.1 Routes du projet]]
31. [[#8.2 Fichiers importants]]
32. [[#8.3 Questions types d'oral]]
33. [[#8.4 Modifications courantes]]

---

# Partie 1 — Comprendre Symfony

## 1.1 Architecture MVC

### Schéma

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   MODÈLE    │     │    VUE      │     │ CONTRÔLEUR  │
│   (Model)   │     │   (View)    │     │(Controller) │
├─────────────┤     ├─────────────┤     ├─────────────┤
│ Données     │     │ Templates   │     │ Logique     │
│ Store.php   │     │ Twig        │     │ Routes      │
│ (tableaux)  │     │ HTML        │     │ Actions     │
└─────────────┘     └─────────────┘     └─────────────┘
       ↑                   ↑                   │
       │                   │                   │
       └───────────────────┴───────────────────┘
              Le contrôleur coordonne tout
```

### Rôle de chaque partie

| Composant | Rôle | Dans EventCampus |
|-----------|------|------------------|
| **Modèle** | Gère les données | `Store.php` avec tableaux PHP |
| **Vue** | Affiche les données | Templates Twig |
| **Contrôleur** | Coordonne M et V | `AccueilController`, `EvenementController`, etc. |

### Flux d'une requête

```
Utilisateur tape : /evenements/3
         ↓
    Symfony Route
         ↓
EvenementController::show(3)
         ↓
    Store::getEvenementById(3)
         ↓
    render('show.html.twig', ['event' => $event])
         ↓
    Twig génère le HTML
         ↓
    Réponse HTTP → Navigateur
```

---

## 1.2 Structure des dossiers

```
eventcampus/
├── assets/                 ← CSS, JS (frontend)
│   ├── app.js             ← Point d'entrée JavaScript
│   └── styles/
│       └── app.css        ← Styles personnalisés
│
├── config/                 ← Configuration Symfony
│   ├── packages/          ← Config par bundle
│   ├── routes.yaml        ← Routes globales
│   └── services.yaml      ← Services et injection
│
├── docs/                   ← Documentation (ce fichier)
│
├── public/                 ← Dossier web accessible
│   └── index.php          ← Front controller
│
├── src/                    ← Code PHP
│   ├── Controller/        ← Contrôleurs
│   │   ├── AccueilController.php
│   │   ├── EvenementController.php
│   │   └── ApiController.php
│   ├── Service/           ← Services métier
│   │   └── Store.php      ← Données des événements
│   └── Kernel.php         ← Cœur Symfony
│
├── templates/              ← Templates Twig
│   ├── base.html.twig     ← Template racine
│   ├── layout/
│   │   └── main.html.twig ← Layout avec container
│   ├── components/        ← Composants réutilisables
│   ├── home/              ← Page d'accueil
│   ├── evenement/         ← Pages événements
│   └── statistiques/      ← Page statistiques
│
├── var/                    ← Cache, logs (généré)
├── vendor/                 ← Dépendances (généré)
│
├── composer.json           ← Dépendances PHP
└── importmap.php           ← Dépendances JS (AssetMapper)
```

### Dossiers importants pour toi

| Dossier | Ce que tu y fais |
|---------|------------------|
| `src/Controller/` | Modifier/créer des contrôleurs |
| `src/Service/` | Modifier les données (Store) |
| `templates/` | Modifier l'affichage (Twig) |
| `assets/styles/` | Modifier le CSS |

---

## 1.3 Le cycle de vie d'une requête

### Étape par étape

```php
// 1. L'utilisateur accède à /evenements/3

// 2. Symfony cherche la route correspondante
#[Route('/evenements/{id}', name: 'app_evenements_show')]

// 3. Symfony appelle la méthode du contrôleur
public function show(int $id): Response

// 4. Le contrôleur récupère les données via le Service
$event = $this->store->getEvenementById($id);

// 5. Le contrôleur passe les données à Twig
return $this->render('evenement/show.html.twig', [
    'event' => $event,
]);

// 6. Twig génère le HTML

// 7. Symfony renvoie la Response au navigateur
```

---

# Partie 2 — Les Contrôleurs

## 2.1 Qu'est-ce qu'un contrôleur

Un contrôleur est une **classe PHP** qui :
1. Reçoit une requête HTTP
2. Traite la logique métier
3. Retourne une réponse (HTML ou JSON)

### Structure de base

```php
<?php

namespace App\Controller;  // Espace de noms

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MonController extends AbstractController
{
    #[Route('/chemin', name: 'nom_route')]
    public function maMethode(): Response
    {
        return $this->render('template.html.twig', [
            'variable' => 'valeur',
        ]);
    }
}
```

### Anatomie ligne par ligne

```php
namespace App\Controller;
```
→ Organise le code. Tous les contrôleurs sont dans `App\Controller`.

```php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
```
→ Importe la classe parente qui fournit des méthodes utiles.

```php
final class MonController extends AbstractController
```
→ `final` = ne peut pas être étendue. `extends` = hérite des méthodes.

```php
#[Route('/chemin', name: 'nom_route')]
```
→ Attribut PHP 8 qui définit la route.

```php
public function maMethode(): Response
```
→ Méthode publique qui retourne un objet `Response`.

---

## 2.2 Les routes avec attributs

### Route simple

```php
#[Route('/', name: 'app_accueil')]
public function index(): Response
```

| Élément | Valeur | Description |
|---------|--------|-------------|
| `/` | Chemin URL | Ce que l'utilisateur voit |
| `app_accueil` | Nom de route | Utilisé par `path()` dans Twig |

### Route avec paramètre

```php
#[Route('/evenements/{id}', name: 'app_evenements_show')]
public function show(int $id): Response
```

- `{id}` dans l'URL devient le paramètre `$id` de la méthode
- Symfony fait la conversion automatiquement

### Route avec contrainte (requirements)

```php
#[Route('/evenements/{id}', name: 'app_evenements_show', requirements: ['id' => '\d+'])]
```

| Contrainte | Signification |
|------------|---------------|
| `\d+` | Un ou plusieurs chiffres |
| `[a-z]+` | Une ou plusieurs lettres minuscules |
| `[a-zA-Z0-9]+` | Alphanumérique |

> [!important] Pourquoi requirements ?
> Sans contrainte, `/evenements/abc` matcherait la route et causerait une erreur.
> Avec `\d+`, seuls `/evenements/1`, `/evenements/42` sont valides.

### Route avec méthode HTTP

```php
#[Route('/api/evenements', name: 'api_evenements', methods: ['GET'])]
```

→ Cette route n'accepte que les requêtes GET (pas POST, PUT, DELETE).

### Préfixe de route (sur la classe)

```php
#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/evenements', name: 'api_evenements')]
    // URL finale : /api/evenements
}
```

---

## 2.3 Injection de dépendances

### Concept

Au lieu de créer les objets toi-même, Symfony te les **injecte**.

### Sans injection (mauvais)

```php
public function index(): Response
{
    $store = new Store();  // ❌ Tu crées l'objet toi-même
    $events = $store->getEvenement();
}
```

### Avec injection (bon)

```php
private Store $store;

public function __construct(Store $store)
{
    $this->store = $store;  // ✅ Symfony te donne l'objet
}

public function index(): Response
{
    $events = $this->store->getEvenement();
}
```

### Pourquoi c'est mieux ?

1. **Testable** : on peut remplacer `Store` par un mock en test
2. **Découplé** : le contrôleur ne dépend pas de la création du Store
3. **Configurable** : Symfony peut configurer le Store ailleurs

---

## 2.4 Méthodes utiles d'AbstractController

### render() — Afficher un template

```php
return $this->render('evenement/show.html.twig', [
    'event' => $event,
    'titre' => 'Mon titre',
]);
```

### json() — Retourner du JSON

```php
return $this->json([
    'success' => true,
    'data' => $evenements,
]);
```

### redirectToRoute() — Rediriger

```php
return $this->redirectToRoute('app_evenements');

// Avec paramètres
return $this->redirectToRoute('app_evenements_show', ['id' => 5]);
```

### createNotFoundException() — Erreur 404

```php
if ($event === null) {
    throw $this->createNotFoundException('Événement non trouvé.');
}
```

### addFlash() — Message flash

```php
$this->addFlash('success', 'Opération réussie !');
$this->addFlash('warning', 'Attention !');
$this->addFlash('error', 'Une erreur est survenue.');
```

---

# Partie 3 — Le Service Store

## 3.1 Qu'est-ce qu'un service

Un **service** est une classe PHP qui effectue une tâche spécifique.

Dans EventCampus, `Store` est le service qui **gère les données** des événements.

### Pourquoi un service ?

```
┌─────────────────────────────────────────────┐
│              Sans service                    │
│  Contrôleur A → données en dur              │
│  Contrôleur B → données en dur (dupliquées) │
│  Contrôleur C → données en dur (dupliquées) │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│              Avec service                    │
│  Contrôleur A ─┐                            │
│  Contrôleur B ─┼─→ Store (données centralisées)
│  Contrôleur C ─┘                            │
└─────────────────────────────────────────────┘
```

---

## 3.2 Structure des données événements

### Champs obligatoires (CDC)

```php
[
    'id' => 1,                              // Identifiant unique (entier)
    'titre' => 'Nom de l\'événement',       // Titre (string)
    'description' => 'Description...',      // Description longue (string)
    'date_debut' => '2024-10-31 20:00:00',  // Date/heure début (string)
    'date_fin' => '2024-11-01 02:00:00',    // Date/heure fin (string)
    'lieu' => 'Amphithéâtre Central',       // Lieu (string)
    'categorie' => 'festif',                // Catégorie (string)
    'organisateur' => 'BDE Campus',         // Organisateur (string)
    'prix' => 8.0,                          // Prix en euros (float)
    'places_disponibles' => 150,            // Places restantes (int)
    'places_totales' => 200,                // Capacité totale (int)
    'image' => 'halloween.jpg',             // Nom fichier image (string)
    'statut' => 'ouvert'                    // Statut (string)
]
```

### Catégories valides

| Catégorie | Description |
|-----------|-------------|
| `culturel` | Conférences, expositions, théâtre |
| `sportif` | Tournois, marathons, compétitions |
| `associatif` | Collectes, ateliers, bénévolat |
| `festif` | Soirées, galas, concerts |

### Statuts valides

| Statut | Signification |
|--------|---------------|
| `ouvert` | Inscriptions possibles |
| `complet` | Plus de places |
| `annulé` | Événement annulé |

---

## 3.3 Méthodes du Store

### getEvenement() — Tous les événements

```php
public function getEvenement(): array
{
    return [
        1 => [...],
        2 => [...],
        // ...
    ];
}
```

**Retour** : Tableau associatif `[id => événement]`

### get3Evenement() — 3 derniers événements

```php
public function get3Evenement(): array
{
    return array_slice($this->getEvenement(), -3, 3);
}
```

**Explication** :
- `array_slice($array, -3, 3)` = prend les 3 derniers éléments
- `-3` = commence 3 positions avant la fin

### getEvenementById() — Un événement par ID

```php
public function getEvenementById(int $id): ?array
{
    $evenements = $this->getEvenement();
    return $evenements[$id] ?? null;
}
```

**Explication** :
- `$evenements[$id]` = accède à l'événement avec cet ID
- `?? null` = opérateur null coalescent, retourne `null` si l'ID n'existe pas
- `?array` = peut retourner un tableau OU null

### getEvenementsByCategorie() — Filtrer par catégorie

```php
public function getEvenementsByCategorie(string $categorie): array
{
    return array_filter($this->getEvenement(), function ($e) use ($categorie) {
        return $e['categorie'] === $categorie;
    });
}
```

**Explication** :
- `array_filter()` = garde uniquement les éléments qui passent le test
- `function ($e)` = fonction anonyme qui teste chaque événement
- `use ($categorie)` = importe la variable `$categorie` dans la fonction
- `$e['categorie'] === $categorie` = le test de filtrage

---

# Partie 4 — Twig (Templates)

## 4.1 Syntaxe de base

### Deux types de balises

| Balise | Usage | Exemple |
|--------|-------|---------|
| `{{ }}` | **Afficher** une valeur | `{{ event.titre }}` |
| `{% %}` | **Exécuter** du code | `{% if condition %}` |
| `{# #}` | **Commentaire** | `{# Ceci est ignoré #}` |

### Accéder aux données

```twig
{# Depuis un tableau associatif PHP #}
{{ event.titre }}
{{ event['titre'] }}

{# Les deux syntaxes sont équivalentes #}
```

### Afficher avec HTML échappé (sécurité)

```twig
{{ event.description }}
```
→ Les caractères `<`, `>`, `&` sont automatiquement échappés.

### Afficher du HTML brut (dangereux)

```twig
{{ event.contenuHTML|raw }}
```
→ À utiliser uniquement si tu contrôles le contenu.

---

## 4.2 Héritage de templates

### Concept

```
┌─────────────────────────────────────┐
│         base.html.twig              │
│  ┌───────────────────────────────┐  │
│  │        {% block body %}       │  │
│  │                               │  │
│  │   Rempli par l'enfant         │  │
│  │                               │  │
│  │        {% endblock %}         │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

### Template parent (base.html.twig)

```twig
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}Défaut{% endblock %}</title>
</head>
<body>
    {% block body %}{% endblock %}
</body>
</html>
```

### Template enfant

```twig
{% extends 'base.html.twig' %}

{% block title %}Ma Page{% endblock %}

{% block body %}
    <h1>Contenu de ma page</h1>
{% endblock %}
```

### Résultat HTML

```html
<!DOCTYPE html>
<html>
<head>
    <title>Ma Page</title>
</head>
<body>
    <h1>Contenu de ma page</h1>
</body>
</html>
```

### Chaîne d'héritage EventCampus

```
base.html.twig           ← Structure HTML globale
    ↓ extends
layout/main.html.twig    ← Container Bootstrap + flash messages
    ↓ extends
home/index.html.twig     ← Contenu spécifique
```

---

## 4.3 Include et composants

### Syntaxe

```twig
{% include 'chemin/template.html.twig' %}
```

### Avec variables

```twig
{% include 'components/evenement_card.html.twig' with {event: monEvent} %}
```

### Composants EventCampus

| Composant | Fichier | Utilisé dans |
|-----------|---------|--------------|
| Navigation | `components/navigation.html.twig` | `base.html.twig` |
| Footer | `components/footer.html.twig` | `base.html.twig` |
| Carte événement | `components/evenement_card.html.twig` | Pages liste |
| Flash messages | `components/flash_messages.html.twig` | `layout/main.html.twig` |

### Exemple : Carte événement

```twig
{# Dans evenement/index.html.twig #}
{% for event in events %}
    <div class="col">
        {% include 'components/evenement_card.html.twig' with {event: event} %}
    </div>
{% endfor %}
```

---

## 4.4 Variables avec set

### Créer une variable

```twig
{% set maVariable = 'valeur' %}
{% set nombre = 42 %}
{% set tableau = ['a', 'b', 'c'] %}
```

### Calcul

```twig
{% set placesOccupees = event.places_totales - event.places_disponibles %}
```

### Avec ternaire

```twig
{% set pourcentage = event.places_totales > 0 
    ? (placesOccupees / event.places_totales * 100) 
    : 0 %}
```

### Dans EventCampus (show.html.twig)

```twig
{% set placesOccupees = event.places_totales - event.places_disponibles %}
{% set pourcentage = event.places_totales > 0 ? (placesOccupees / event.places_totales * 100) : 0 %}

<p>{{ placesOccupees }} places occupées ({{ pourcentage|round }}%)</p>
```

---

## 4.5 Conditions

### if simple

```twig
{% if event.prix > 0 %}
    <span>{{ event.prix }}€</span>
{% endif %}
```

### if / else

```twig
{% if event.prix > 0 %}
    <span>{{ event.prix }}€</span>
{% else %}
    <span>Gratuit</span>
{% endif %}
```

### if / elseif / else

```twig
{% if event.statut == 'ouvert' %}
    <span class="badge bg-success">Ouvert</span>
{% elseif event.statut == 'complet' %}
    <span class="badge bg-danger">Complet</span>
{% else %}
    <span class="badge bg-secondary">{{ event.statut }}</span>
{% endif %}
```

### Opérateurs de comparaison

| Opérateur | Signification | Exemple |
|-----------|---------------|---------|
| `==` | Égal | `a == b` |
| `!=` | Différent | `a != b` |
| `>` | Supérieur | `a > b` |
| `<` | Inférieur | `a < b` |
| `>=` | Supérieur ou égal | `a >= b` |
| `<=` | Inférieur ou égal | `a <= b` |

### Tests spéciaux

| Test | Signification | Exemple |
|------|---------------|---------|
| `is empty` | Est vide | `{% if events is empty %}` |
| `is not empty` | N'est pas vide | `{% if events is not empty %}` |
| `is defined` | Existe | `{% if variable is defined %}` |
| `is null` | Est null | `{% if variable is null %}` |

### Opérateurs logiques

```twig
{% if condition1 and condition2 %}
{% if condition1 or condition2 %}
{% if not condition %}
```

---

## 4.6 Opérateur ternaire

### Syntaxe

```twig
{{ condition ? valeurSiVrai : valeurSiFaux }}
```

### Exemples

```twig
{# Prix #}
{{ event.prix > 0 ? event.prix ~ '€' : 'Gratuit' }}

{# Pluriel #}
{{ count }} événement{{ count > 1 ? 's' : '' }}

{# Classe CSS conditionnelle #}
<span class="badge {{ event.statut == 'ouvert' ? 'bg-success' : 'bg-danger' }}">
```

### Dans les classes CSS (EventCampus)

```twig
<span class="badge 
    {% if event.statut == 'ouvert' %}bg-success
    {% elseif event.statut == 'complet' %}bg-danger
    {% else %}bg-secondary{% endif %}">
    {{ event.statut|capitalize }}
</span>
```

### Concaténation avec ~

```twig
{{ 'Prix : ' ~ event.prix ~ '€' }}
```

---

## 4.7 Boucles

### for simple

```twig
{% for event in events %}
    <p>{{ event.titre }}</p>
{% endfor %}
```

### for avec clé-valeur

```twig
{% for categorie, nombre in repartitionCategories %}
    <p>{{ categorie }} : {{ nombre }}</p>
{% endfor %}
```

**PHP équivalent** :
```php
foreach ($repartitionCategories as $categorie => $nombre) {
    echo "$categorie : $nombre";
}
```

### Variables de boucle (loop)

```twig
{% for event in events %}
    {{ loop.index }}      {# 1, 2, 3... #}
    {{ loop.index0 }}     {# 0, 1, 2... #}
    {{ loop.first }}      {# true si premier #}
    {{ loop.last }}       {# true si dernier #}
    {{ loop.length }}     {# nombre total #}
{% endfor %}
```

### for avec else (liste vide)

```twig
{% for event in events %}
    <p>{{ event.titre }}</p>
{% else %}
    <p>Aucun événement trouvé.</p>
{% endfor %}
```

---

## 4.8 Filtres

### Syntaxe

```twig
{{ valeur|filtre }}
{{ valeur|filtre(argument) }}
{{ valeur|filtre1|filtre2 }}  {# Chaînage #}
```

### Filtres utilisés dans EventCampus

| Filtre | Description | Exemple | Résultat |
|--------|-------------|---------|----------|
| `capitalize` | Première lettre majuscule | `{{ 'hello'\|capitalize }}` | `Hello` |
| `upper` | Tout en majuscules | `{{ 'hello'\|upper }}` | `HELLO` |
| `lower` | Tout en minuscules | `{{ 'HELLO'\|lower }}` | `hello` |
| `date` | Formater une date | `{{ date\|date('d/m/Y') }}` | `31/10/2024` |
| `round` | Arrondir | `{{ 3.7\|round }}` | `4` |
| `length` | Longueur | `{{ events\|length }}` | `8` |
| `default` | Valeur par défaut | `{{ var\|default('N/A') }}` | `N/A` si null |

### Format de date

| Code | Signification | Exemple |
|------|---------------|---------|
| `d` | Jour (01-31) | 31 |
| `m` | Mois (01-12) | 10 |
| `Y` | Année 4 chiffres | 2024 |
| `H` | Heure (00-23) | 20 |
| `i` | Minutes (00-59) | 30 |

```twig
{{ event.date_debut|date('d/m/Y à H:i') }}
{# Résultat : 31/10/2024 à 20:00 #}
```

---

## 4.9 Fonctions

### path() — Générer une URL

```twig
{# Route sans paramètre #}
{{ path('app_accueil') }}
{# → / #}

{# Route avec paramètre #}
{{ path('app_evenements_show', {id: 5}) }}
{# → /evenements/5 #}

{# Route avec plusieurs paramètres #}
{{ path('app_evenements_categorie', {categorie: 'sportif'}) }}
{# → /evenements/categorie/sportif #}
```

### include() — Version fonction

```twig
{{ include('components/footer.html.twig') }}
```

### 'now'|date() — Date actuelle

```twig
{{ 'now'|date('Y') }}
{# → 2024 #}
```

---

# Partie 5 — API JSON

## 5.1 Différence HTML vs JSON

| Aspect | Page Web (HTML) | API (JSON) |
|--------|-----------------|------------|
| Consommateur | Navigateur humain | Application mobile, autre service |
| Format | HTML avec CSS | JSON brut |
| Rendu | Visuel | Données structurées |
| Template | Twig | Aucun |

### Exemple HTML

```html
<div class="card">
    <h2>Soirée Halloween</h2>
    <p>31/10/2024</p>
</div>
```

### Exemple JSON

```json
{
    "id": 1,
    "titre": "Soirée Halloween",
    "date_debut": "2024-10-31 20:00:00"
}
```

---

## 5.2 JsonResponse

### Méthode json() du contrôleur

```php
return $this->json([
    'success' => true,
    'data' => $evenements,
]);
```

### Résultat

```json
{
    "success": true,
    "data": [
        {"id": 1, "titre": "..."},
        {"id": 2, "titre": "..."}
    ]
}
```

### Avec code HTTP personnalisé

```php
return $this->json([
    'success' => false,
    'error' => 'Non trouvé',
], Response::HTTP_NOT_FOUND);  // 404
```

---

## 5.3 Gestion des erreurs API

### Code dans ApiController

```php
#[Route('/evenements/{id}', name: 'api_evenements_show')]
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
```

### Réponses possibles

**Succès (200)** :
```json
{
    "success": true,
    "data": {
        "id": 1,
        "titre": "Soirée Halloween"
    }
}
```

**Erreur (404)** :
```json
{
    "success": false,
    "error": "Événement non trouvé",
    "code": 404
}
```

---

# Partie 6 — Bootstrap

## 6.1 Installation avec AssetMapper

### Commande

```bash
php bin/console importmap:require bootstrap
```

### Configuration (assets/app.js)

```javascript
import 'bootstrap/dist/css/bootstrap.min.css';  // CSS
import 'bootstrap';                              // JS (dropdowns, etc.)
import './styles/app.css';                       // Tes styles après
```

---

## 6.2 Composants utilisés

### Navbar

```html
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Logo</a>
        
        {# Bouton hamburger (mobile) #}
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        {# Menu qui se collapse #}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Lien</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

### Dropdown

```html
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        Menu déroulant
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Item 1</a></li>
        <li><a class="dropdown-item" href="#">Item 2</a></li>
    </ul>
</li>
```

### Card

```html
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Titre</h5>
        <p class="card-text">Contenu</p>
    </div>
    <div class="card-footer">
        <a href="#" class="btn btn-primary">Action</a>
    </div>
</div>
```

### Badge

```html
<span class="badge bg-success">Ouvert</span>
<span class="badge bg-danger">Complet</span>
<span class="badge bg-warning text-dark">Attention</span>
```

### Alert

```html
<div class="alert alert-success">Message de succès</div>
<div class="alert alert-danger">Message d'erreur</div>
<div class="alert alert-warning">Message d'avertissement</div>
<div class="alert alert-info">Message d'information</div>
```

### Progress bar

```html
<div class="progress">
    <div class="progress-bar bg-success" style="width: 75%">75%</div>
</div>
```

---

## 6.3 Système de grille

### Concept

```
┌──────────────────────────────────────┐
│              container               │
│  ┌────────────────────────────────┐  │
│  │             row                │  │
│  │  ┌────┐  ┌────┐  ┌────┐       │  │
│  │  │col │  │col │  │col │       │  │
│  │  └────┘  └────┘  └────┘       │  │
│  └────────────────────────────────┘  │
└──────────────────────────────────────┘
```

### Classes responsive

```html
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">Colonne 1</div>
    <div class="col">Colonne 2</div>
    <div class="col">Colonne 3</div>
</div>
```

| Classe | Écran | Colonnes |
|--------|-------|----------|
| `row-cols-1` | Mobile (< 768px) | 1 |
| `row-cols-md-2` | Tablette (≥ 768px) | 2 |
| `row-cols-lg-3` | Desktop (≥ 992px) | 3 |
| `g-4` | Tous | Gap (espacement) |

### Breakpoints Bootstrap

| Préfixe | Taille minimale |
|---------|-----------------|
| (aucun) | 0px |
| `sm` | 576px |
| `md` | 768px |
| `lg` | 992px |
| `xl` | 1200px |
| `xxl` | 1400px |

---

## 6.4 Classes utilitaires

### Espacement

```html
<div class="p-4">  {# padding: 1.5rem #}
<div class="m-3">  {# margin: 1rem #}
<div class="py-4"> {# padding-top et padding-bottom #}
<div class="mb-3"> {# margin-bottom #}
<div class="mt-5"> {# margin-top #}
```

### Texte

```html
<p class="text-center">Centré</p>
<p class="text-muted">Grisé</p>
<p class="fw-bold">Gras</p>
<p class="fs-5">Taille 5</p>
```

### Couleurs

```html
<span class="text-primary">Bleu</span>
<span class="text-success">Vert</span>
<span class="text-danger">Rouge</span>
<span class="text-warning">Jaune</span>
<span class="bg-primary text-white">Fond bleu</span>
```

### Display

```html
<div class="d-flex">Flexbox</div>
<div class="d-none d-md-block">Caché sur mobile</div>
<div class="d-flex justify-content-between">Espace entre</div>
<div class="d-flex align-items-center">Centré verticalement</div>
```

---

# Partie 7 — Fonctionnalités avancées

## 7.1 Messages flash

### Dans le contrôleur

```php
$this->addFlash('success', 'Opération réussie !');
$this->addFlash('warning', 'Attention !');
$this->addFlash('error', 'Une erreur est survenue.');

return $this->redirectToRoute('app_accueil');
```

### Dans le template (flash_messages.html.twig)

```twig
{% for label, messages in app.flashes %}
    {% for message in messages %}
        <div class="alert alert-{{ label == 'error' ? 'danger' : label }} alert-dismissible fade show">
            {{ message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    {% endfor %}
{% endfor %}
```

### Fonctionnement

1. Le contrôleur stocke le message en session
2. Après redirection, le template affiche le message
3. Le message est automatiquement supprimé après affichage

---

## 7.2 Page 404 personnalisée

### Emplacement

```
templates/bundles/TwigBundle/Exception/error404.html.twig
```

### Contenu

```twig
{% extends 'base.html.twig' %}

{% block title %}Page non trouvée{% endblock %}

{% block body %}
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <p class="lead">Page non trouvée</p>
    <a href="{{ path('app_accueil') }}" class="btn btn-primary">
        Retour à l'accueil
    </a>
</div>
{% endblock %}
```

### Déclenchement

```php
throw $this->createNotFoundException('Message d\'erreur');
```

---

## 7.3 Validation des données

### Vérifier une catégorie valide

```php
#[Route('/evenements/categorie/{categorie}')]
public function categorie(string $categorie): Response
{
    $categoriesValides = ['culturel', 'sportif', 'associatif', 'festif'];

    if (!in_array($categorie, $categoriesValides)) {
        $this->addFlash('warning', 'Catégorie non reconnue.');
        return $this->redirectToRoute('app_evenements');
    }

    // Suite du code...
}
```

---

# Partie 8 — Récapitulatif pour l'évaluation

## 8.1 Routes du projet

| Nom | URL | Méthode | Description |
|-----|-----|---------|-------------|
| `app_accueil` | `/` | GET | Page d'accueil |
| `app_evenements` | `/evenements` | GET | Liste des événements |
| `app_evenements_show` | `/evenements/{id}` | GET | Détail événement |
| `app_evenements_categorie` | `/evenements/categorie/{cat}` | GET | Filtre catégorie |
| `app_statistiques` | `/statistiques` | GET | Statistiques |
| `api_evenements` | `/api/evenements` | GET | API JSON liste |
| `api_evenements_show` | `/api/evenements/{id}` | GET | API JSON détail |

---

## 8.2 Fichiers importants

### Contrôleurs

| Fichier | Responsabilité |
|---------|----------------|
| `AccueilController.php` | Page d'accueil (3 derniers événements) |
| `EvenementController.php` | Liste, détail, catégorie, statistiques |
| `ApiController.php` | API JSON |

### Service

| Fichier | Responsabilité |
|---------|----------------|
| `Store.php` | Données des 8 événements |

### Templates

| Fichier | Responsabilité |
|---------|----------------|
| `base.html.twig` | Structure HTML globale |
| `layout/main.html.twig` | Container + flash messages |
| `components/navigation.html.twig` | Navbar Bootstrap |
| `components/evenement_card.html.twig` | Carte réutilisable |
| `components/footer.html.twig` | Pied de page |
| `home/index.html.twig` | Accueil |
| `evenement/index.html.twig` | Liste événements |
| `evenement/show.html.twig` | Détail événement |
| `evenement/categorie.html.twig` | Filtre catégorie |
| `statistiques/index.html.twig` | Statistiques |

---

## 8.3 Questions types d'oral

### Sur Symfony

**Q: Qu'est-ce que MVC ?**
> Architecture séparant Modèle (données), Vue (affichage) et Contrôleur (logique).

**Q: À quoi sert une route ?**
> Associe une URL à une méthode de contrôleur.

**Q: Qu'est-ce que l'injection de dépendances ?**
> Symfony fournit automatiquement les objets dont le contrôleur a besoin.

**Q: Que fait `render()` ?**
> Génère une Response HTML à partir d'un template Twig.

### Sur Twig

**Q: Différence entre `{{ }}` et `{% %}` ?**
> `{{ }}` affiche, `{% %}` exécute du code.

**Q: À quoi sert `extends` ?**
> Hériter d'un template parent pour réutiliser sa structure.

**Q: À quoi sert `include` ?**
> Insérer un template dans un autre (composants).

**Q: Qu'est-ce qu'un filtre ?**
> Transforme une valeur. Ex: `|capitalize`, `|date`.

### Sur l'API

**Q: Différence entre HTML et JSON ?**
> HTML pour humains (navigateur), JSON pour machines (apps).

**Q: Comment retourner du JSON ?**
> `return $this->json(['data' => $data]);`

**Q: Comment gérer une erreur 404 en API ?**
> Retourner un JSON avec code HTTP 404.

---

## 8.4 Modifications courantes

### Ajouter un événement

Dans `Store.php`, ajouter dans le tableau :

```php
9 => [
    'id' => 9,
    'titre' => 'Nouvel événement',
    // ... autres champs
],
```

### Ajouter une route

Dans le contrôleur :

```php
#[Route('/ma-nouvelle-route', name: 'app_nouvelle')]
public function nouvelle(): Response
{
    return $this->render('mon_template.html.twig', []);
}
```

### Ajouter une catégorie

1. Ajouter dans `EvenementController::categorie()` :
```php
$categoriesValides = ['culturel', 'sportif', 'associatif', 'festif', 'nouveau'];
```

2. Ajouter le lien dans `navigation.html.twig`

3. Créer des événements avec cette catégorie dans `Store.php`

### Modifier l'affichage d'une carte

Éditer `components/evenement_card.html.twig`.

### Ajouter un champ aux statistiques

1. Calculer dans `EvenementController::statistiques()`
2. Passer à Twig : `'nouveauChamp' => $valeur`
3. Afficher dans `statistiques/index.html.twig`

---

# Annexe — Commandes utiles

### Démarrer le serveur

```bash
symfony server:start
```

### Lister les routes

```bash
php bin/console debug:router
```

### Vérifier les templates

```bash
php bin/console lint:twig templates/
```

### Vider le cache

```bash
php bin/console cache:clear
```

### Installer une dépendance JS

```bash
php bin/console importmap:require nom_package
```

---

> [!success] Fin de l'encyclopédie
> Tu as maintenant toutes les connaissances pour comprendre, expliquer et modifier EventCampus.
> Bonne chance pour ton évaluation !
