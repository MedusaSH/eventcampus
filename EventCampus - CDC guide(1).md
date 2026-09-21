# EventCampus — Cahier des charges guidé

> [!info] But de cette version
> Cette version reprend le cahier des charges original EventCampus et le transforme en parcours de revue guidé.
>
> **Principe :** on considère que l’étudiant repart de zéro. Chaque exigence du CDC est décomposée en étapes avec les notions à comprendre, le travail à réaliser et un critère de validation.

---

# 0. Comprendre le projet avant de coder

## 0.1 Contexte

EventCampus est une plateforme web destinée à centraliser et promouvoir les événements étudiants du campus.

L’application doit permettre de :

- [ ] Consulter la liste des événements à venir
- [ ] Afficher le détail complet d’un événement
- [ ] Filtrer les événements par catégorie
- [ ] Fournir une API pour une future application mobile
- [ ] Proposer une interface moderne et responsive

## 0.2 Objectifs pédagogiques

L’application doit démontrer la maîtrise de :

- [ ] Architecture MVC avec Symfony
- [ ] Contrôleurs et routage
- [ ] Templates Twig avec héritage et composants
- [ ] Intégration d’un framework CSS moderne
- [ ] Gestion des erreurs et expérience utilisateur

## 0.3 Vision globale

```text
Navigateur
    ↓
Route Symfony
    ↓
Controller
    ↓
Données / traitement
    ↓
Twig ou JSON
    ↓
Response
    ↓
Utilisateur
```

### Validation

- [ ] Je peux expliquer à quoi sert EventCampus.
- [ ] Je peux citer ses grandes fonctionnalités.
- [ ] Je sais distinguer une page web d’une API.
- [ ] Je sais expliquer globalement le rôle d’un Controller et de Twig.

---

# PHASE 1 — STRUCTURE ET CONFIGURATION

> Temps indicatif du CDC : 2 à 3 h

# 1. Créer le projet Symfony

## Objectif

Créer et démarrer le projet EventCampus.

Commande du CDC :

```bash
symfony new eventcampus --webapp
cd eventcampus
```

## À comprendre

- [ ] Qu’est-ce qu’un projet Symfony ?
- [ ] À quoi sert `src/` ?
- [ ] À quoi sert `templates/` ?
- [ ] À quoi sert `public/` ?
- [ ] Quel est le rôle de Symfony ?

## Travail

- [ ] Créer le projet.
- [ ] Démarrer le serveur.
- [ ] Vérifier que l’application fonctionne.

## Validation

> Je dois pouvoir lancer le projet et expliquer où se trouvent le code PHP et les templates Twig.

---

# 2. Comprendre MVC

```text
Navigateur
    ↓
URL
    ↓
Route Symfony
    ↓
Controller
    ↓
Données / traitement
    ↓
Twig
    ↓
HTML
```

## À savoir expliquer

### Model

Représente les données.

Dans cette version du CDC, les données de test sont stockées sous forme de tableaux PHP.

### View

Twig affiche les données.

### Controller

Le Controller reçoit la demande, récupère ou traite les données puis produit une réponse.

### Validation

- [ ] Je peux expliquer MVC avec EventCampus.
- [ ] Je sais expliquer le trajet d’une requête.

---

# 3. Créer les trois Controllers

Le CDC demande :

```text
AccueilController
EvenementController
ApiController
```

## 3.1 AccueilController

Responsabilité :

- [ ] Page d’accueil
- [ ] Aperçu des prochains événements
- [ ] Statistiques générales

## 3.2 EvenementController

Responsabilité :

- [ ] Liste des événements
- [ ] Filtrage par catégorie
- [ ] Détail d’un événement
- [ ] Statistiques

## 3.3 ApiController

Responsabilité :

- [ ] API de tous les événements
- [ ] API d’un événement précis
- [ ] Réponses JSON
- [ ] Gestion des erreurs API

## Validation

- [ ] Les trois Controllers existent.
- [ ] Je sais expliquer le rôle de chacun.

---

# PHASE 2 — DONNÉES DE TEST

> Temps indicatif du CDC : 1 h

# 4. Comprendre la structure d’un événement

Chaque événement doit posséder :

```text
id
titre
description
date_debut
date_fin
lieu
categorie
organisateur
prix
places_disponibles
places_totales
image
statut
```

Exemple :

```php
[
    'id' => 1,
    'titre' => 'Soirée Étudiante Halloween',
    'description' => 'Grande soirée costumée pour célébrer Halloween !',
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
]
```

## À comprendre

- [ ] Tableau associatif
- [ ] Clé et valeur
- [ ] Accès à une valeur
- [ ] `foreach`
- [ ] Événement courant

---

# 5. Créer le jeu de données

Le CDC demande **au minimum 8 événements variés**.

Catégories obligatoires :

```text
culturel
sportif
associatif
festif
```

## Travail

- [ ] Créer au moins 8 événements.
- [ ] Utiliser tous les champs demandés.
- [ ] Varier les catégories.
- [ ] Varier les places.
- [ ] Varier les statuts.
- [ ] Vérifier la cohérence des données.

## Validation

Je dois pouvoir expliquer ce que contient :

```php
$events
```

et ce que contient :

```php
$event
```

dans un `foreach`.

---

# PHASE 3 — ROUTAGE ET CONTRÔLEURS

> Temps indicatif du CDC : 3 à 4 h

# 6. Les routes obligatoires

| URL | Fonction |
|---|---|
| `/` | Accueil |
| `/evenements` | Liste complète |
| `/evenements/categorie/{categorie}` | Filtrage |
| `/evenements/{id}` | Détail |
| `/statistiques` | Statistiques |
| `/api/evenements` | API complète |
| `/api/evenements/{id}` | API détail |

---

# 7. Construire `/`

## Objectif

Créer la page d’accueil avec un aperçu des prochains événements.

## Travail

- [ ] Créer la route.
- [ ] Créer l’action.
- [ ] Récupérer les événements.
- [ ] Choisir les événements à présenter.
- [ ] Envoyer les données à Twig.
- [ ] Afficher les données.

## Notions

- [ ] `#[Route]`
- [ ] `Response`
- [ ] `render()`
- [ ] Passage Controller → Twig
- [ ] `foreach`

## Validation

La page `/` fonctionne et affiche les événements.

---

# 8. Construire `/evenements`

## Objectif

Afficher la liste complète.

## Travail

- [ ] Créer la route.
- [ ] Récupérer tous les événements.
- [ ] Les envoyer à Twig.
- [ ] Parcourir les événements.
- [ ] Afficher les informations demandées.

## Validation

Tous les événements sont affichés.

---

# 9. Construire `/evenements/categorie/{categorie}`

## Objectif

Filtrer les événements.

Exemples :

```text
/evenements/categorie/culturel
/evenements/categorie/sportif
/evenements/categorie/associatif
/evenements/categorie/festif
```

## Raisonnement

```text
Tous les événements
       ↓
     Filtre
       ↓
Événements correspondants
```

## Travail

- [ ] Récupérer la catégorie depuis la route.
- [ ] Récupérer les événements.
- [ ] Parcourir ou filtrer la collection.
- [ ] Comparer la catégorie.
- [ ] Conserver les correspondances.
- [ ] Envoyer le résultat à Twig.

## Notions

- [ ] Paramètre de route
- [ ] `foreach`
- [ ] `if`
- [ ] Comparaison
- [ ] Tableau résultat
- [ ] `array_filter()` si utilisé

## Validation

- [ ] Une catégorie fonctionne.
- [ ] Les autres événements sont exclus.
- [ ] Une catégorie sans résultat ne provoque pas une erreur.

---

# 10. Construire `/evenements/{id}`

## Objectif

Afficher un événement précis.

Exemple :

```text
/evenements/3
```

## Raisonnement

```text
ID demandé
    ↓
Récupérer les événements
    ↓
Chercher l’événement
    ↓
Trouvé ?
 ├── Oui → afficher
 └── Non → 404
```

## Travail

- [ ] Créer la route.
- [ ] Récupérer l’ID.
- [ ] Récupérer les événements.
- [ ] Chercher l’événement.
- [ ] Vérifier son existence.
- [ ] Afficher le détail.

## Notions

- [ ] Paramètre dynamique
- [ ] `requirements`
- [ ] Recherche
- [ ] Comparaison
- [ ] `empty()`
- [ ] `createNotFoundException()`

## Validation

Tester :

```text
/evenements/1
/evenements/2
/evenements/999
```

---

# 11. Comprendre `requirements`

Exemple :

```php
requirements: ['id' => '\d+']
```

Cela impose un paramètre numérique.

```text
/evenements/42  → correspond
/evenements/abc → ne correspond pas
```

## Validation

- [ ] Je sais expliquer pourquoi cette contrainte existe.
- [ ] Je sais expliquer `\d+`.

---

# 12. Construire `/statistiques`

## Objectif

Créer une page de statistiques.

Cette partie doit être construite progressivement.

---

## 12.1 Compter

Question :

> Combien y a-t-il d’événements ?

Algorithme :

```text
compteur = 0

pour chaque événement
    compteur += 1
```

- [ ] Je sais créer un compteur.
- [ ] Je sais expliquer pourquoi il commence à 0.

---

## 12.2 Additionner

Question :

> Quel est le total des places ?

```text
total = 0

pour chaque événement
    total += places_totales
```

- [ ] Je sais créer un accumulateur.
- [ ] Je sais accéder à une propriété.

---

## 12.3 Calculer une moyenne

```text
moyenne = somme / nombre
```

Je dois identifier :

- [ ] La somme
- [ ] Le nombre
- [ ] Le résultat
- [ ] Le risque de division par zéro

---

## 12.4 Places occupées

```text
places occupées
=
places totales - places disponibles
```

---

## 12.5 Taux d’occupation

```text
taux d’occupation
=
places occupées / places totales × 100
```

Exemple :

```text
120 - 45 = 75

75 / 120 × 100 = 62,5 %
```

- [ ] Je sais calculer les places occupées.
- [ ] Je sais calculer le taux.
- [ ] Je ne confonds plus disponibles / occupées / totales.

---

## 12.6 Répartition par catégorie

Le CDC demande une répartition des événements par catégorie.

Raisonnement :

```text
Tous les événements
       ↓
Identifier la catégorie
       ↓
Regrouper
       ↓
Compter / calculer
```

Catégories :

```text
culturel
sportif
associatif
festif
```

- [ ] Je sais compter les événements d’une catégorie.
- [ ] Je sais organiser le résultat pour Twig.

---

# PHASE 4 — TEMPLATES ET INTERFACE

> Temps indicatif du CDC : 4 à 5 h

# 13. Structure Twig demandée

```text
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

---

# 14. Comprendre l’héritage Twig

Principe :

```text
Template principal
       ↓
Structure commune
       ↓
Page enfant
       ↓
Contenu spécifique
```

À maîtriser :

- [ ] `{% extends %}`
- [ ] `{% block %}`
- [ ] `{{ ... }}`
- [ ] `{% ... %}`

---

# 15. Créer la navigation

Le CDC demande une navigation responsive avec menu déroulant pour les catégories.

Créer :

```text
components/navigation.html.twig
```

- [ ] Navigation générale
- [ ] Liens principaux
- [ ] Catégories
- [ ] Menu déroulant
- [ ] Responsive

---

# 16. Créer une card événement

Créer :

```text
components/evenement_card.html.twig
```

La card doit notamment afficher :

- [ ] Image
- [ ] Titre
- [ ] Date
- [ ] Lieu

Objectif :

> Éviter de recopier le même HTML dans plusieurs pages.

---

# 17. Créer le footer

Créer :

```text
components/footer.html.twig
```

Il doit être réutilisable dans les pages.

---

# 18. Comprendre `include`

Concept :

```text
Page
 ├── Navigation
 ├── Contenu
 └── Footer
```

Au lieu de recopier le même HTML partout.

- [ ] Je comprends le principe d’un composant.
- [ ] Je sais inclure un template.
- [ ] Je sais transmettre des données à un composant si nécessaire.

---

# 19. Page d’accueil

Créer :

```text
home/index.html.twig
```

- [ ] Hériter de la structure commune.
- [ ] Afficher la navigation.
- [ ] Afficher les événements.
- [ ] Utiliser les composants.
- [ ] Respecter le responsive.

---

# 20. Pages événement

Créer :

```text
evenement/
├── index.html.twig
├── show.html.twig
└── categorie.html.twig
```

## `index.html.twig`

Liste complète.

## `show.html.twig`

Détail complet.

## `categorie.html.twig`

Résultat du filtre.

---

# 21. Page statistiques

Créer :

```text
statistiques/index.html.twig
```

Le Controller réalise les traitements.

Twig présente les résultats.

```text
Controller
    ↓
Calculs
    ↓
Variables
    ↓
Twig
    ↓
Affichage
```

---

# 22. Bootstrap ou Tailwind

Le CDC autorise :

```text
Bootstrap
OU
Tailwind
```

Objectifs :

- [ ] Interface moderne
- [ ] Responsive
- [ ] Navigation responsive
- [ ] Cards
- [ ] Filtres visuels
- [ ] Page détail lisible

> Le CDC laisse le choix du framework CSS.

---

# PHASE 5 — FONCTIONNALITÉS AVANCÉES

> Temps indicatif du CDC : 2 à 3 h

# 23. Gestion des erreurs 404

Le CDC demande une gestion d’erreurs 404 personnalisées.

Cas principal :

```text
Événement inexistant
        ↓
404
```

- [ ] Je sais détecter l’absence d’un événement.
- [ ] Je sais déclencher une 404.
- [ ] Je comprends pourquoi une 404 est nécessaire.

---

# 24. Places restantes dynamiques

Le CDC demande un calcul dynamique des places restantes.

Données :

```text
places_disponibles
places_totales
```

Je dois comprendre quelles données représentent :

- [ ] Capacité totale
- [ ] Places disponibles
- [ ] Places occupées
- [ ] Information à afficher à l’utilisateur

---

# 25. Affichage selon le statut

Statuts à tester :

```text
ouvert
complet
annulé
```

Raisonnement :

```text
SI ouvert
    affichage ouvert

SINON SI complet
    affichage complet

SINON SI annulé
    affichage annulé
```

Notions :

- [ ] `if`
- [ ] `elseif`
- [ ] `else`
- [ ] Comparaison
- [ ] Affichage conditionnel Twig

---

# PHASE 6 — API

# 26. Comprendre l’API JSON

Une page web retourne généralement du HTML.

Une API retourne des données structurées, ici en JSON.

Exemple :

```json
[
    {
        "id": 1,
        "titre": "Soirée Étudiante Halloween"
    }
]
```

---

# 27. `/api/evenements`

## Objectif

Retourner tous les événements en JSON.

## Travail

- [ ] Créer la route.
- [ ] Créer l’action dans `ApiController`.
- [ ] Récupérer les événements.
- [ ] Retourner du JSON.
- [ ] Tester l’endpoint.

## Notion

```text
JsonResponse
```

---

# 28. `/api/evenements/{id}`

## Objectif

Retourner un événement précis en JSON.

Raisonnement :

```text
ID
 ↓
Recherche
 ↓
Trouvé ?
 ├── Oui → JSON
 └── Non → erreur
```

- [ ] Récupérer l’ID.
- [ ] Rechercher l’événement.
- [ ] Vérifier son existence.
- [ ] Retourner le JSON.
- [ ] Gérer l’erreur.

---

# 29. Gestion des erreurs API

Tester :

```text
/api/evenements
/api/evenements/1
/api/evenements/9999
```

Validation :

- [ ] La liste fonctionne.
- [ ] Le détail fonctionne.
- [ ] L’événement inexistant est correctement géré.
- [ ] Je comprends la différence entre réponse HTML et JSON.

---

# PHASE 7 — MESSAGES FLASH

Le CDC mentionne les messages flash dans les exigences d’interface.

## À comprendre

Un message flash permet d’afficher temporairement une information à l’utilisateur.

- [ ] Comprendre le principe d’un flash message.
- [ ] Savoir où il est créé.
- [ ] Savoir comment il est affiché dans Twig.

> Le CDC mentionne les messages flash mais ne précise pas une fonctionnalité métier précise qui doit les déclencher. Ne pas inventer une fonctionnalité supplémentaire.

---

# PHASE 8 — RECETTE FINALE

# 30. Tester toutes les routes

## Pages

- [ ] `/`
- [ ] `/evenements`
- [ ] `/evenements/categorie/culturel`
- [ ] `/evenements/categorie/sportif`
- [ ] `/evenements/categorie/associatif`
- [ ] `/evenements/categorie/festif`
- [ ] `/evenements/1`
- [ ] `/evenements/9999`
- [ ] `/statistiques`

## API

- [ ] `/api/evenements`
- [ ] `/api/evenements/1`
- [ ] `/api/evenements/9999`

---

# 31. Vérifier les données

- [ ] Minimum 8 événements.
- [ ] Les 4 catégories sont présentes.
- [ ] Tous les champs sont présents.
- [ ] Les données sont cohérentes.
- [ ] Plusieurs statuts sont testables.

---

# 32. Vérifier Twig

- [ ] Héritage fonctionnel.
- [ ] Navigation fonctionnelle.
- [ ] Composants réutilisés.
- [ ] Cards fonctionnelles.
- [ ] Liste fonctionnelle.
- [ ] Détail fonctionnel.
- [ ] Catégorie fonctionnelle.
- [ ] Statistiques fonctionnelles.
- [ ] Affichage conditionnel fonctionnel.

---

# 33. Vérifier l’algorithmique

Je dois savoir :

- [ ] Parcourir un tableau.
- [ ] Compter.
- [ ] Additionner.
- [ ] Rechercher.
- [ ] Filtrer.
- [ ] Calculer une moyenne.
- [ ] Calculer des places occupées.
- [ ] Calculer un pourcentage.
- [ ] Regrouper par catégorie.
- [ ] Gérer l’absence de résultat.

---

# 34. Vérifier Symfony

Je dois pouvoir expliquer :

- [ ] Route
- [ ] Paramètre de route
- [ ] `requirements`
- [ ] Controller
- [ ] Injection de dépendance
- [ ] Service
- [ ] `Response`
- [ ] `render()`
- [ ] `JsonResponse`
- [ ] `createNotFoundException()`

---

# 35. Vérifier Twig

Je dois pouvoir utiliser :

- [ ] `{{ }}`
- [ ] `{% %}`
- [ ] `for`
- [ ] `if`
- [ ] `extends`
- [ ] `block`
- [ ] `include`
- [ ] Composants
- [ ] Affichage conditionnel

---

# 36. Simulation d’évaluation

On me donne un CDC similaire.

Je dois être capable de passer de :

```text
CDC
 ↓
Données nécessaires
 ↓
Routes nécessaires
 ↓
Controllers
 ↓
Données de test
 ↓
Liste
 ↓
Filtrage
 ↓
Détail
 ↓
Erreurs
 ↓
Statistiques
 ↓
Twig
 ↓
Interface
 ↓
API
 ↓
Tests
```

sans avoir besoin qu’on me donne directement le code.

---

# 37. Questions d’oral

## Symfony

### Pourquoi un Controller peut-il hériter de `AbstractController` ?

Réponse personnelle :

> ...

### Que fait une route ?

> ...

### Que devient `{id}` dans `/evenements/{id}` ?

> ...

### Pourquoi utiliser `requirements` ?

> ...

---

## PHP / algorithmique

### Différence entre compteur et accumulateur ?

> ...

### Comment chercher un événement par ID ?

> ...

### Que faire si aucun événement ne correspond ?

> ...

### Comment calculer une moyenne ?

> ...

### Comment calculer un taux d’occupation ?

> ...

---

## Twig

### À quoi sert `extends` ?

> ...

### À quoi sert `block` ?

> ...

### Différence entre `{{ }}` et `{% %}` ?

> ...

### Pourquoi utiliser des composants ?

> ...

---

## API

### Qu’est-ce qu’une API ?

> ...

### Qu’est-ce qu’un endpoint ?

> ...

### Différence HTML / JSON ?

> ...

### Comment gérer un événement inexistant ?

> ...

---

# 38. Progression globale

```text
[ ] 0. Comprendre EventCampus
        ↓
[ ] 1. Créer Symfony
        ↓
[ ] 2. Comprendre MVC
        ↓
[ ] 3. Créer les Controllers
        ↓
[ ] 4. Créer les données
        ↓
[ ] 5. Construire /
        ↓
[ ] 6. Construire /evenements
        ↓
[ ] 7. Filtrer par catégorie
        ↓
[ ] 8. Afficher un événement
        ↓
[ ] 9. Gérer la 404
        ↓
[ ] 10. Construire les statistiques
        ↓
[ ] 11. Comprendre Twig
        ↓
[ ] 12. Créer les composants
        ↓
[ ] 13. Construire l’interface
        ↓
[ ] 14. Ajouter les fonctionnalités conditionnelles
        ↓
[ ] 15. Construire l’API
        ↓
[ ] 16. Gérer les erreurs API
        ↓
[ ] 17. Recette
        ↓
[ ] 18. Simulation d’évaluation
```

---

# 39. Méthode de travail pendant la revue

Pour chaque étape :

```text
1. Comprendre
       ↓
2. Décomposer
       ↓
3. Coder seul
       ↓
4. Tester
       ↓
5. Corriger
       ↓
6. Expliquer avec ses propres mots
       ↓
7. Valider
```

> [!important] Règle
> Si le code fonctionne mais que je ne peux pas expliquer ce qu’il fait, l’étape n’est pas considérée comme maîtrisée.

---

# 40. Validation finale

EventCampus est considéré comme revu lorsque je peux :

- [ ] Lire le CDC et identifier les fonctionnalités.
- [ ] Identifier les données nécessaires.
- [ ] Construire les routes.
- [ ] Créer les Controllers.
- [ ] Récupérer les données.
- [ ] Parcourir une collection.
- [ ] Filtrer.
- [ ] Rechercher un événement.
- [ ] Gérer une 404.
- [ ] Faire les calculs statistiques.
- [ ] Envoyer les données à Twig.
- [ ] Construire des templates Twig.
- [ ] Réutiliser des composants.
- [ ] Faire des conditions Twig.
- [ ] Retourner du JSON.
- [ ] Gérer une erreur API.
- [ ] Tester toutes les routes.
- [ ] Expliquer le fonctionnement de l’application à l’oral.
