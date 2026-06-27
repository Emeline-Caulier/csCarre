# Acrylia

Application e-commerce développée en PHP 8.2 et PostgreSQL 18, conteneurisée avec Docker. Le site est une boutique en ligne de bijoux en plexiglas avec un site public (catalogue, panier, commande, compte client, avis) et un back-office d'administration (produits, catégories, promotions, commandes, clients, avis, messages, configuration de l'accueil).

Acrylia est une **marque fictive** créée pour les besoins du projet. L'ensemble des visuels (logo, photos de produits, bannières) ont été générés par intelligence artificielle. Aucune ressource visuelle d'une marque existante n'est utilisée.

Projet réalisé dans un cadre pédagogique : l'effort a été concentré sur le cœur e-commerce (catalogue, panier, commande, espace client, back-office). Certains éléments périphériques visibles dans l'interface — sélecteur de langue, liens du footer (politiques, FAQ), passerelle de paiement, envoi d'emails — sont laissés intentionnellement en placeholder et hors du périmètre.

---

## Sommaire

- [Acrylia](#acrylia)
  - [Sommaire](#sommaire)
  - [Stack technique](#stack-technique)
  - [Installation](#installation)
  - [Comptes de démonstration](#comptes-de-démonstration)
    - [Administrateur](#administrateur)
    - [Clients](#clients)
  - [Fonctionnalités](#fonctionnalités)
    - [Côté client](#côté-client)
    - [Côté administration](#côté-administration)
  - [Architecture du code](#architecture-du-code)
  - [Points techniques](#points-techniques)
  - [Modèle de données](#modèle-de-données)
  - [Contact](#contact)

---

## Stack technique

| Brique | Version | Rôle |
|---|---|---|
| PHP | 8.2 (`php:8.2-apache`) | Langage serveur |
| PostgreSQL | 18 | Base de données |
| Apache | 2.4 | Serveur HTTP |
| PDO + `pdo_pgsql` | — | Couche d'accès BDD (requêtes préparées) |
| Bootstrap + Bootstrap Icons | 5.3.8 / 1.11.0 | CSS et icônes |
| jQuery | 3.7.1 | DOM + AJAX |
| SortableJS | 1.15.3 | Drag-and-drop des images produit |
| Docker / Docker Compose | — | Orchestration des conteneurs |

Aucune dépendance Composer : le code PHP est natif, structuré autour d'un autoloader maison.

---

## Installation

**Pré-requis** : Docker Desktop (ou Docker Engine + Compose sur Linux) et Git. Aucun PHP ni PostgreSQL local n'est nécessaire.

```bash
git clone https://github.com/<votre-utilisateur>/acrylia.git
cd acrylia
cp .env.example .env          # adapter user/password
docker-compose up -d --build
```

Le premier démarrage exécute automatiquement `initdb/init.sql` (schéma + données de démonstration).

**Accès** :
- Boutique : http://localhost:8080/index_.php
- Administration : http://localhost:8080/admin/index_.php
- PostgreSQL exposé sur `localhost:5433` (pour pgAdmin, DBeaver, etc.)

Les points d'entrée sont nommés `index_.php` (underscore final, convention du cours) et doivent être tapés explicitement dans l'URL. Ce nommage est intentionnel : Apache ne reconnaît pas `index_.php` comme `DirectoryIndex` par défaut, ce qui force à voir le routage du front controller (`?page=...`) directement dans la barre d'adresse.

**Reset complet** (volume supprimé, données effacées) : `docker-compose down -v`

---

## Comptes de démonstration

**Mot de passe commun : `1234`**

### Administrateur

| Email | Nom |
|---|---|
| `admin@admin.com` | Test Admin |

### Clients

| Email | Nom |
|---|---|
| `lea.martin@test.com` | Léa Martin |
| `hugo.lefebvre@test.com` | Hugo Lefebvre |
| `camille.dubois@test.com` | Camille Dubois |
| `noah.lambert@test.com` | Noah Lambert |
| `manon.petit@test.com` | Manon Petit |
| `theo.janssens@test.com` | Théo Janssens |

> Le mot de passe `1234` ne respecte pas les contraintes du formulaire d'inscription (8 caractères minimum avec lettre et chiffre). Il fonctionne uniquement pour ces comptes pré-existants.

---

## Fonctionnalités

### Côté client

- **Accueil** : bannière éditable, carrousel de promotions, section « À propos », derniers avis
- **Catalogue** : navigation par catégorie, tri prix, filtres nouveautés/promotions
- **Fiche produit** : galerie, prix barré si promo, badges stock, avis vérifiés, produits similaires
- **Panier** : persistant par session, vérification de stock, frais de port calculés serveur, validation transactionnelle
- **Espace client** : tableau de bord, profil, historique, dépôt d'avis sur produits commandés, formulaire de contact
- **Favoris** : toggle depuis n'importe quelle vignette, rattachement au compte à la connexion
- **Recherche** : live avec dropdown de suggestions, insensible aux accents

### Côté administration

- **Tableau de bord** : KPI commandes/clients/produits/CA, top ventes, alertes
- **Inventaire** : CRUD produits et catégories, vue cartes ou tableau éditable (édition inline AJAX), galerie d'images avec drag-and-drop, soft delete
- **Promotions** : ajout, édition inline du taux et des dates
- **Configuration accueil** : édition du hero et de la section « À propos », gestion de la nouvelle collection
- **Commandes** : liste filtrable par statut, mise à jour statut commande et paiement
- **Modération** : avis (approuvé/refusé), messages (non lu / lu / répondu)

---

## Architecture du code

```
acrylia/
├── Docker-compose.yaml         # Orchestration des conteneurs
├── Dockerfile                  # Image PHP 8.2 + Apache + extensions PostgreSQL
├── .env / .env.example
├── index_.php                  # Front controller PUBLIC
│
├── content/                    # Vues PUBLIQUES
│   ├── accueil.php, catalogue.php, produit.php, panier.php, …
│   ├── compte.php + compte_post.php
│   └── vues/                   # Composants réutilisables + sous-vues du compte
│
├── admin/
│   ├── index_.php              # Front controller ADMIN
│   ├── content/                # Vues ADMIN (accueil, inventaire, commandes, …)
│   ├── assets/
│   │   ├── css/style.css       # Feuille de style unique (variables --theme-*)
│   │   ├── images/             # Logo, badges, uploads
│   │   └── js/                 # Un fichier par fonctionnalité
│   │
│   └── src/php/
│       ├── db/db_pg_connect.php           # Chargement env + DSN + PDO
│       ├── classes/                       # Entités + DAO
│       │   ├── Autoloader.class.php
│       │   ├── Connexion.class.php        # Factory PDO
│       │   └── (entité + DAO pour Admin, Client, Produit, Categorie,
│       │       Commande, Avis, Message, Configuration, Promotion,
│       │       ListeEnvie, Panier)
│       │
│       ├── ajax/                          # 13 endpoints AJAX
│       └── utils/
│           ├── all_includes.php           # Bootstrap (BDD + autoload + helpers)
│           ├── header.php / footer.php / public_menu.php / admin_menu.php
│           ├── check_connexion.php        # Garde d'auth admin
│           ├── accueil_data.php           # Stats du tableau de bord
│           └── fonctions_utilitaires.php  # Helpers (e(), uploads sécurisés, redirections)
│
├── initdb/init.sql             # Schéma + fonctions + vues + seed
└── backups/                    # Définitions archivées + dumps ponctuels
```

Deux points d'entrée (`index_.php` public et admin) routent vers la vue demandée via `?page=`. Chaque entité métier dispose d'une classe DTO et d'une classe DAO (`ProduitDAO`, `ClientDAO`, `CommandeDAO`, `PanierDAO`...) — toutes les requêtes SQL sont confinées dans les DAO. Le JavaScript est organisé par fonctionnalité (un fichier pour le panier, un pour les favoris, un pour la recherche, etc.).

---

## Points techniques

**Pattern DAO + DTO.** Chaque entité métier a sa classe DTO (`Produit`, `Client`...) et sa classe DAO. Les DAO renvoient des objets hydratés. Aucune requête SQL n'est écrite dans une vue ou un contrôleur.

**Logique métier en PL/pgSQL.** Les opérations sensibles à la cohérence (création de commande, ajout au panier, toggle favori) sont implémentées en fonctions PL/pgSQL côté base. La création d'une commande regroupe en une transaction l'insertion de la commande, des lignes et le décrément du stock.

```sql
SELECT public.creer_commande(:id_client, :id_panier, :total);
SELECT public.ajouter_produit_panier(:id_panier, :id_produit, :quantite);
```

**Panier et favoris persistants.** Un visiteur non connecté peut remplir son panier et ses favoris : tout est rattaché à son `session_id`. À la connexion, `lier_client_liste_envie` et `get_ou_creer_panier` réassocient les éléments au compte.

**Upload d'image sécurisé.** `uploadImageSecurisee()` valide successivement la taille, l'extension (whitelist), le type MIME réel via `getimagesize()` (rejette un `.php` renommé en `.jpg`), puis renomme aléatoirement avant déplacement.

**Gestion du type `money`.** Le type natif PostgreSQL `money` est sensible à la locale serveur (en `en_US`, lecture en `$15.00`). Tous les SELECT portant sur un prix appliquent le cast `prix::numeric::text AS prix` pour un parsing fiable côté PHP.

---

## Modèle de données

16 tables couvrant le domaine e-commerce :

- **Catalogue** : `categorie`, `produit`, `image_produit`, `promotion`
- **Compte** : `admin`, `client`, `adresse`
- **Commande** : `commande`, `commande_produit` (avec snapshot du prix au moment de l'achat)
- **Panier / favoris** : `panier`, `panier_produit`, `liste_envie` (rattachables à `id_session` ou `id_client`)
- **Avis et messagerie** : `avis`, `message_contact`
- **Configuration** : `configuration` (textes et images du hero, « à propos »...)

6 vues SQL pour les jointures fréquentes (`v_avis_approuves`, `v_avis_details`, `v_commandes_client`, `v_donnees_client`, `v_messages_contact`, `v_produits_promotion`).

32 fonctions PL/pgSQL réparties par domaine : produits, catégories, images, promotions, panier, commandes, favoris, clients, avis, messages. L'extension PostgreSQL `unaccent` est utilisée pour la recherche.

---

## Contact

Emeline Caulier — étudiante en Bachelier Informatique, orientation développement d'applications.

---

*Projet pédagogique. Code à but de démonstration, sans licence commerciale.*
