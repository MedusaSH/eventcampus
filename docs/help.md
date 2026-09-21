# EventCampus — Guide Complet Phase 4

> [!info] À propos de ce document
> Ce document explique **tout** ce qui a été implémenté dans la Phase 4 du projet EventCampus.
> Il est conçu pour un étudiant BTS SIO SLAM souhaitant comprendre et maîtriser Symfony + Twig + Bootstrap.

---

## Table des matières

1. [[#Architecture des Templates]]
2. [[#Héritage Twig — extends et block]]
3. [[#Inclusion de Composants — include]]
4. [[#Variables Twig — set]]
5. [[#Conditions Twig — if, elseif, else]]
6. [[#Opérateur Ternaire — ? :]]
7. [[#Boucles — for]]
8. [[#Filtres Twig]]
9. [[#Fonctions Twig]]
10. [[#Symfony — Routes avec path()]]
11. [[#Contrôleurs Refactorisés]]
12. [[#Bootstrap dans Symfony]]
13. [[#Récapitulatif Syntaxique]]

---

# Architecture des Templates

## Structure CDC

```
templates/
├── base.html.twig              ← Template racine (HTML, head, body)
├── layout/
│   └── main.html.twig          ← Layout principal (container Bootstrap)
├── components/
│   ├── navigation.html.twig    ← Navbar Bootstrap
│   ├── evenement_card.html.twig ← Carte réutilisable
│   └── footer.html.twig        ← Pied de page
├── home/
│   └── index.html.twig         ← Page d'accueil
├── evenement/
│   ├── index.html.twig         ← Liste des événements
│   ├── show.html.twig          ← Détail d'un événement
│   └── categorie.html.twig     ← Filtre par catégorie
└── statistiques/
    └── index.html.twig         ← Page statistiques
```

## Principe de l'architecture

```
┌─────────────────────────────────────────────┐
│                base.html.twig               │
│  ┌───────────────────────────────────────┐  │
│  │        navigation.html.twig           │  │
│  └───────────────────────────────────────┘  │
│  ┌───────────────────────────────────────┐  │
│  │           layout/main.html.twig       │  │
│  │  ┌─────────────────────────────────┐  │  │
│  │  │      Page spécifique            │  │  │
│  │  │   (home, evenement, stats...)   │  │  │
│  │  └─────────────────────────────────┘  │  │
│  └───────────────────────────────────────┘  │
│  ┌───────────────────────────────────────┐  │
│  │          footer.html.twig             │  │
│  └───────────────────────────────────────┘  │
└─────────────────────────────────────────────┘
```

---

# Héritage Twig — extends et block

## Concept

L'héritage permet de définir un **template parent** et de le **personnaliser** dans les templates enfants.

## Syntaxe

```twig
{# Template parent (base.html.twig) #}
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}Titre par défaut{% endblock %}</title>
</head>
<body>
    {% block body %}{% endblock %}
</body>
</html>
```

```twig
{# Template enfant (home/index.html.twig) #}
{% extends 'base.html.twig' %}

{% block title %}Accueil - EventCampus{% endblock %}

{% block body %}
    <h1>Bienvenue</h1>
{% endblock %}
```

## Dans EventCampus

### base.html.twig (racine)

```twig
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>{% block title %}EventCampus{% endblock %}</title>
    {% block stylesheets %}{% endblock %}
    {% block javascripts %}
        {{ importmap('app') }}
    {% endblock %}
</head>
<body>
    {% include 'components/navigation.html.twig' %}
    <main>
        {% block body %}{% endblock %}
    </main>
    {% include 'components/footer.html.twig' %}
</body>
</html>
```

### layout/main.html.twig (intermédiaire)

```twig
{% extends 'base.html.twig' %}

{% block body %}
<div class="container py-4">
    {% block content %}{% endblock %}
</div>
{% endblock %}
```

> [!important] Pourquoi un layout intermédiaire ?
> - `base.html.twig` = structure HTML globale
> - `layout/main.html.twig` = mise en page Bootstrap (container)
> - Les pages héritent de `layout/main.html.twig` et utilisent `{% block content %}`

### Page finale (home/index.html.twig)

```twig
{% extends 'layout/main.html.twig' %}

{% block title %}Accueil - EventCampus{% endblock %}

{% block content %}
    <h1>Bienvenue sur EventCampus</h1>
{% endblock %}
```

## Chaîne d'héritage

```
base.html.twig
    ↓ extends
layout/main.html.twig
    ↓ extends
home/index.html.twig (ou evenement/index.html.twig, etc.)
```

---

# Inclusion de Composants — include

## Concept

`include` permet d'**insérer** un template dans un autre, comme une pièce de puzzle.

## Syntaxe de base

```twig
{% include 'chemin/vers/template.html.twig' %}
```

## Avec passage de variables

```twig
{% include 'components/evenement_card.html.twig' with {event: monEvenement} %}
```

## Dans EventCampus

### Navigation et Footer (dans base.html.twig)

```twig
<body>
    {% include 'components/navigation.html.twig' %}
    
    <main>
        {% block body %}{% endblock %}
    </main>
    
    {% include 'components/footer.html.twig' %}
</body>
```

### Carte événement (dans les pages)

```twig
{% for event in events %}
    <div class="col">
        {% include 'components/evenement_card.html.twig' with {event: event} %}
    </div>
{% endfor %}
```

> [!tip] Avantage
> La carte est définie **une seule fois** dans `evenement_card.html.twig`.
> On la réutilise partout sans dupliquer le HTML.

---

# Variables Twig — set

## Concept

`set` permet de **créer une variable** directement dans Twig.

## Syntaxe

```twig
{% set nomVariable = valeur %}
```

## Dans EventCampus (show.html.twig)

```twig
{% set placesOccupees = event.places_totales - event.places_disponibles %}
{% set pourcentage = event.places_totales > 0 ? (placesOccupees / event.places_totales * 100) : 0 %}
```

### Explication ligne par ligne

**Ligne 1 :**
```twig
{% set placesOccupees = event.places_totales - event.places_disponibles %}
```
- Crée une variable `placesOccupees`
- Calcule : places totales − places disponibles = places prises

**Ligne 2 :**
```twig
{% set pourcentage = event.places_totales > 0 ? (placesOccupees / event.places_totales * 100) : 0 %}
```
- Crée une variable `pourcentage`
- Utilise l'opérateur ternaire (voir section suivante)

## Autres exemples

```twig
{# Variable simple #}
{% set titre = "Mon titre" %}

{# Variable numérique #}
{% set compteur = 0 %}

{# Variable booléenne #}
{% set estActif = true %}

{# Variable tableau #}
{% set categories = ['culturel', 'sportif', 'festif'] %}
```

---

# Conditions Twig — if, elseif, else

## Syntaxe de base

```twig
{% if condition %}
    {# Code si vrai #}
{% endif %}
```

## Avec else

```twig
{% if condition %}
    {# Code si vrai #}
{% else %}
    {# Code si faux #}
{% endif %}
```

## Avec elseif

```twig
{% if condition1 %}
    {# Code si condition1 vraie #}
{% elseif condition2 %}
    {# Code si condition2 vraie #}
{% else %}
    {# Code sinon #}
{% endif %}
```

## Dans EventCampus

### Affichage du statut (evenement_card.html.twig)

```twig
<span class="badge 
    {% if event.statut == 'ouvert' %}bg-success
    {% elseif event.statut == 'complet' %}bg-danger
    {% else %}bg-secondary{% endif %}">
    {{ event.statut|capitalize }}
</span>
```

**Explication :**
- Si `statut == 'ouvert'` → classe CSS `bg-success` (vert)
- Sinon si `statut == 'complet'` → classe CSS `bg-danger` (rouge)
- Sinon → classe CSS `bg-secondary` (gris)

### Affichage du prix

```twig
{% if event.prix > 0 %}
    <span class="badge bg-info">{{ event.prix }}€</span>
{% else %}
    <span class="badge bg-success">Gratuit</span>
{% endif %}
```

### Liste vide

```twig
{% if events is empty %}
    <div class="alert alert-info">
        Aucun événement disponible.
    </div>
{% else %}
    {# Afficher les événements #}
{% endif %}
```

## Opérateurs de comparaison

| Opérateur | Signification |
|-----------|---------------|
| `==` | Égal à |
| `!=` | Différent de |
| `>` | Supérieur à |
| `<` | Inférieur à |
| `>=` | Supérieur ou égal |
| `<=` | Inférieur ou égal |
| `is empty` | Est vide |
| `is not empty` | N'est pas vide |
| `is defined` | Est défini |
| `is null` | Est null |

## Opérateurs logiques

```twig
{% if condition1 and condition2 %}
    {# Les deux sont vraies #}
{% endif %}

{% if condition1 or condition2 %}
    {# Au moins une est vraie #}
{% endif %}

{% if not condition %}
    {# La condition est fausse #}
{% endif %}
```

---

# Opérateur Ternaire — ? :

## Concept

L'opérateur ternaire est une **condition en une ligne**.

## Syntaxe

```twig
{{ condition ? valeurSiVrai : valeurSiFaux }}
```

## Équivalent if/else

```twig
{# Ternaire #}
{{ event.prix > 0 ? event.prix ~ '€' : 'Gratuit' }}

{# Équivalent if/else #}
{% if event.prix > 0 %}
    {{ event.prix }}€
{% else %}
    Gratuit
{% endif %}
```

## Dans EventCampus

### Calcul du pourcentage (show.html.twig)

```twig
{% set pourcentage = event.places_totales > 0 ? (placesOccupees / event.places_totales * 100) : 0 %}
```

**Traduction en français :**
> Si `places_totales > 0`, alors calcule le pourcentage, sinon mets 0.

**Pourquoi cette vérification ?**
→ Éviter la division par zéro (erreur mathématique).

### Calcul de moyenne (statistiques/index.html.twig)

```twig
{% set pourcentage = nbEvenements > 0 ? (nombre / nbEvenements * 100) : 0 %}
```

### Pluriel conditionnel

```twig
{{ nombre }} événement{{ nombre > 1 ? 's' : '' }}
```

**Résultat :**
- Si `nombre = 1` → "1 événement"
- Si `nombre = 3` → "3 événements"

## Ternaire imbriqué (déconseillé mais possible)

```twig
{% set couleur = pourcentage >= 90 ? 'danger' : (pourcentage >= 70 ? 'warning' : 'success') %}
```

> [!warning] Lisibilité
> Pour les cas complexes, préfère `if/elseif/else` classique.

---

# Boucles — for

## Syntaxe de base

```twig
{% for element in collection %}
    {{ element }}
{% endfor %}
```

## Avec index

```twig
{% for event in events %}
    {{ loop.index }} - {{ event.titre }}
{% endfor %}
```

## Variables de boucle (loop)

| Variable | Description |
|----------|-------------|
| `loop.index` | Index courant (commence à 1) |
| `loop.index0` | Index courant (commence à 0) |
| `loop.first` | `true` si premier élément |
| `loop.last` | `true` si dernier élément |
| `loop.length` | Nombre total d'éléments |

## Dans EventCampus

### Liste d'événements

```twig
{% for event in events %}
    <div class="col">
        {% include 'components/evenement_card.html.twig' with {event: event} %}
    </div>
{% endfor %}
```

### Répartition par catégorie (statistiques)

```twig
{% for categorie, nombre in repartitionCategories %}
    <div class="mb-3">
        <span class="fw-bold">{{ categorie|capitalize }}</span>
        <span>{{ nombre }} événement{{ nombre > 1 ? 's' : '' }}</span>
    </div>
{% endfor %}
```

> [!note] Boucle clé-valeur
> `for categorie, nombre in repartitionCategories` permet d'itérer sur un tableau associatif PHP :
> - `categorie` = la clé (ex: "culturel", "sportif")
> - `nombre` = la valeur (ex: 2, 3)

### Boucle avec else (liste vide)

```twig
{% for event in events %}
    {{ event.titre }}
{% else %}
    Aucun événement trouvé.
{% endfor %}
```

---

# Filtres Twig

## Concept

Les filtres **transforment** une valeur. Syntaxe : `valeur|filtre`

## Filtres utilisés dans EventCampus

### capitalize

Première lettre en majuscule.

```twig
{{ event.statut|capitalize }}
```
- `"ouvert"` → `"Ouvert"`
- `"complet"` → `"Complet"`

### date

Formate une date.

```twig
{{ event.date_debut|date('d/m/Y à H:i') }}
```
- `"2024-10-31 20:00:00"` → `"31/10/2024 à 20:00"`

**Codes de format :**

| Code | Signification | Exemple |
|------|---------------|---------|
| `d` | Jour (01-31) | 31 |
| `m` | Mois (01-12) | 10 |
| `Y` | Année (4 chiffres) | 2024 |
| `H` | Heure (00-23) | 20 |
| `i` | Minutes (00-59) | 00 |

### round

Arrondit un nombre.

```twig
{{ pourcentage|round }}
```
- `62.5` → `63`
- `62.4` → `62`

## Autres filtres utiles

```twig
{# Majuscules #}
{{ "hello"|upper }}  → "HELLO"

{# Minuscules #}
{{ "HELLO"|lower }}  → "hello"

{# Longueur #}
{{ events|length }}  → nombre d'événements

{# Valeur par défaut #}
{{ variable|default('Valeur par défaut') }}

{# Échapper HTML (sécurité) #}
{{ contenuHTML|escape }}
{{ contenuHTML|e }}  {# raccourci #}

{# Formater un nombre #}
{{ 1234.56|number_format(2, ',', ' ') }}  → "1 234,56"
```

---

# Fonctions Twig

## Concept

Les fonctions **génèrent** une valeur. Syntaxe : `fonction(arguments)`

## Fonctions utilisées dans EventCampus

### path()

Génère une URL à partir d'un nom de route.

```twig
{{ path('app_accueil') }}
```
→ Génère `/`

```twig
{{ path('app_evenements_show', {id: event.id}) }}
```
→ Génère `/evenements/3` (si `event.id = 3`)

```twig
{{ path('app_evenements_categorie', {categorie: 'culturel'}) }}
```
→ Génère `/evenements/categorie/culturel`

> [!important] Toujours utiliser path()
> **Ne jamais** écrire les URLs en dur dans les templates.
> Les routes peuvent changer, `path()` s'adaptera automatiquement.

### include()

Inclut un template (version fonction).

```twig
{{ include('components/footer.html.twig') }}
```

### 'now'|date()

Date actuelle.

```twig
{{ 'now'|date('Y') }}
```
→ `"2026"` (année actuelle)

---

# Symfony — Routes avec path()

## Définition des routes (Controller)

```php
#[Route('/evenements', name: 'app_evenements')]
public function index(): Response
```

| Élément | Valeur | Description |
|---------|--------|-------------|
| `/evenements` | Chemin URL | Ce que l'utilisateur voit |
| `app_evenements` | Nom de route | Ce que Twig utilise |

## Route avec paramètre

```php
#[Route('/evenements/{id}', name: 'app_evenements_show', requirements: ['id' => '\d+'])]
public function show(int $id): Response
```

| Élément | Description |
|---------|-------------|
| `{id}` | Paramètre dynamique |
| `requirements: ['id' => '\d+']` | Contrainte : chiffres uniquement |

## Utilisation dans Twig

```twig
{# Route sans paramètre #}
<a href="{{ path('app_evenements') }}">Événements</a>

{# Route avec paramètre #}
<a href="{{ path('app_evenements_show', {id: event.id}) }}">Détail</a>

{# Route avec plusieurs paramètres #}
<a href="{{ path('app_evenements_categorie', {categorie: 'culturel'}) }}">Culturel</a>
```

## Tableau des routes EventCampus

| Nom de route | URL | Paramètres |
|--------------|-----|------------|
| `app_accueil` | `/` | aucun |
| `app_evenements` | `/evenements` | aucun |
| `app_evenements_show` | `/evenements/{id}` | `id` (entier) |
| `app_evenements_categorie` | `/evenements/categorie/{categorie}` | `categorie` (string) |
| `app_statistiques` | `/statistiques` | aucun |

---

# Contrôleurs Refactorisés

## Avant / Après

### Route événements

**Avant :**
```php
#[Route('/evenement', name: 'app_evenement')]
```

**Après :**
```php
#[Route('/evenements', name: 'app_evenements')]
```

> [!note] Pourquoi ?
> Le CDC demande `/evenements` (pluriel).

### Fonction show (détail événement)

**Avant :**
```php
public function id(int $id): Response
{
    $event = $this->store->getEvenement();
    $event = array_filter($event, function ($l) use ($id) {
        return $l['id'] === $id;
    });
    
    if (empty($event)) {
        throw $this->createNotFoundException();
    }
    
    return $this->render('evenement/evenement.html.twig', [
        'events' => $event,  // Tableau avec un seul élément
    ]);
}
```

**Après :**
```php
public function show(int $id): Response
{
    $events = $this->store->getEvenement();
    $event = null;

    foreach ($events as $e) {
        if ($e['id'] === $id) {
            $event = $e;
            break;
        }
    }

    if ($event === null) {
        throw $this->createNotFoundException('Événement non trouvé.');
    }

    return $this->render('evenement/show.html.twig', [
        'event' => $event,  // Un seul événement (pas un tableau)
    ]);
}
```

**Améliorations :**
1. Nom de fonction plus explicite (`show` vs `id`)
2. Retourne un seul événement, pas un tableau
3. Template dédié `show.html.twig`
4. Message d'erreur plus clair

### Fonction statistiques

**Avant (incomplet) :**
```php
public function statistiques(): Response {
    $events = $this->store->getEvenement();
    // ... calculs ...
    // PAS DE RETURN !
}
```

**Après (complet) :**
```php
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
```

---

# Bootstrap dans Symfony

## Installation avec AssetMapper

```bash
php bin/console importmap:require bootstrap
```

Cette commande ajoute dans `importmap.php` :
- `bootstrap` (JS)
- `@popperjs/core` (dépendance pour dropdowns)
- `bootstrap/dist/css/bootstrap.min.css` (CSS)

## Configuration (assets/app.js)

```javascript
import './stimulus_bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';  // CSS Bootstrap
import 'bootstrap';                              // JS Bootstrap
import './styles/app.css';                       // CSS personnalisé (après Bootstrap)
```

> [!important] Ordre des imports
> Le CSS personnalisé doit être importé **après** Bootstrap pour pouvoir surcharger ses styles.

## Composants Bootstrap utilisés

### Navbar

```html
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
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
        Menu
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Item 1</a></li>
        <li><a class="dropdown-item" href="#">Item 2</a></li>
    </ul>
</li>
```

### Cards

```html
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Titre</h5>
        <p class="card-text">Contenu</p>
    </div>
</div>
```

### Grid System

```html
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">Colonne 1</div>
    <div class="col">Colonne 2</div>
    <div class="col">Colonne 3</div>
</div>
```

| Classe | Signification |
|--------|---------------|
| `row` | Conteneur de colonnes |
| `row-cols-1` | 1 colonne sur mobile |
| `row-cols-md-2` | 2 colonnes sur tablette |
| `row-cols-lg-3` | 3 colonnes sur desktop |
| `g-4` | Gap (espacement) de niveau 4 |
| `col` | Colonne automatique |

### Badges

```html
<span class="badge bg-success">Ouvert</span>
<span class="badge bg-danger">Complet</span>
<span class="badge bg-secondary">Annulé</span>
```

### Progress Bar

```html
<div class="progress">
    <div class="progress-bar bg-success" style="width: 75%">
        75%
    </div>
</div>
```

### Alertes

```html
<div class="alert alert-info">Message d'information</div>
<div class="alert alert-danger">Message d'erreur</div>
<div class="alert alert-success">Message de succès</div>
```

---

# Récapitulatif Syntaxique

## Twig — Deux types de balises

| Balise | Usage | Exemple |
|--------|-------|---------|
| `{{ }}` | **Afficher** une valeur | `{{ event.titre }}` |
| `{% %}` | **Exécuter** du code | `{% if condition %}` |

## Mémento rapide

```twig
{# ===== AFFICHAGE ===== #}
{{ variable }}
{{ variable|filtre }}
{{ fonction(argument) }}

{# ===== HÉRITAGE ===== #}
{% extends 'parent.html.twig' %}
{% block nomBloc %}contenu{% endblock %}

{# ===== INCLUSION ===== #}
{% include 'composant.html.twig' %}
{% include 'composant.html.twig' with {var: valeur} %}

{# ===== VARIABLES ===== #}
{% set maVariable = valeur %}
{% set maVariable = condition ? siVrai : siFaux %}

{# ===== CONDITIONS ===== #}
{% if condition %}
{% elseif autreCondition %}
{% else %}
{% endif %}

{# ===== BOUCLES ===== #}
{% for element in collection %}
{% endfor %}

{% for cle, valeur in tableauAssociatif %}
{% endfor %}

{# ===== ROUTES ===== #}
{{ path('nom_route') }}
{{ path('nom_route', {param: valeur}) }}

{# ===== COMMENTAIRES ===== #}
{# Ceci est un commentaire Twig #}
```

## Filtres courants

| Filtre | Description | Exemple |
|--------|-------------|---------|
| `capitalize` | Première lettre majuscule | `{{ "hello"\|capitalize }}` → `Hello` |
| `upper` | Tout en majuscules | `{{ "hello"\|upper }}` → `HELLO` |
| `lower` | Tout en minuscules | `{{ "HELLO"\|lower }}` → `hello` |
| `date` | Formater une date | `{{ date\|date('d/m/Y') }}` |
| `round` | Arrondir un nombre | `{{ 3.7\|round }}` → `4` |
| `length` | Longueur | `{{ liste\|length }}` |
| `default` | Valeur par défaut | `{{ var\|default('N/A') }}` |

## Opérateurs

| Opérateur | Description |
|-----------|-------------|
| `==` | Égal |
| `!=` | Différent |
| `>`, `<`, `>=`, `<=` | Comparaisons |
| `and` | ET logique |
| `or` | OU logique |
| `not` | Négation |
| `is empty` | Est vide |
| `is defined` | Est défini |
| `? :` | Ternaire |
| `~` | Concaténation de strings |

---

# Exercices de Validation

## Exercice 1 — Comprendre l'héritage

Quelle est la chaîne d'héritage pour `evenement/show.html.twig` ?

<details>
<summary>Réponse</summary>

```
base.html.twig
    ↓ extends
layout/main.html.twig
    ↓ extends
evenement/show.html.twig
```

</details>

## Exercice 2 — Traduire en ternaire

Convertis ce code en opérateur ternaire :

```twig
{% if event.prix > 0 %}
    {{ event.prix }}€
{% else %}
    Gratuit
{% endif %}
```

<details>
<summary>Réponse</summary>

```twig
{{ event.prix > 0 ? event.prix ~ '€' : 'Gratuit' }}
```

</details>

## Exercice 3 — Générer une URL

Écris le code Twig pour générer un lien vers l'événement avec l'ID 5.

<details>
<summary>Réponse</summary>

```twig
<a href="{{ path('app_evenements_show', {id: 5}) }}">Voir l'événement</a>
```

</details>

## Exercice 4 — Boucle avec clé-valeur

Affiche chaque catégorie et son nombre d'événements.

Données : `repartitionCategories = {'culturel': 2, 'sportif': 3, 'festif': 1}`

<details>
<summary>Réponse</summary>

```twig
{% for categorie, nombre in repartitionCategories %}
    <p>{{ categorie|capitalize }} : {{ nombre }}</p>
{% endfor %}
```

</details>

## Exercice 5 — Condition complexe

Affiche un badge avec une couleur différente selon le statut ET le prix.

<details>
<summary>Réponse</summary>

```twig
<span class="badge 
    {% if event.statut == 'annulé' %}
        bg-secondary
    {% elseif event.statut == 'complet' %}
        bg-danger
    {% elseif event.prix == 0 %}
        bg-success
    {% else %}
        bg-primary
    {% endif %}">
    {{ event.statut|capitalize }}
</span>
```

</details>

---

> [!success] Tu as maintenant toutes les clés pour comprendre la Phase 4 !
> N'hésite pas à relire ce document avant ton évaluation.
