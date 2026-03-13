# Syndic Connect

Plateforme de gestion d'incidents pour coproprietes. Les residents peuvent signaler des problemes, suivre l'etat de resolution et echanger avec le syndic. L'administration dispose d'une console pour classer, traiter et repondre rapidement aux incidents, avec assistance IA.

## Table des matieres

- Apercu
- Fonctionnalites
- Stack technique
- Architecture (survol)
- Installation locale
- Configuration
- Lancement
- Usage (guide rapide)
- IA (reponse + digest)
- Export PDF
- Structure des routes
- Tests
- Deploiement (notes)
- Credits

## Apercu

Syndic Connect centralise les signalements d'incidents (photos, localisation, priorite) et fournit un back-office admin pour tri, suivi et reponses. L'IA propose des reponses et un digest hebdomadaire pour gagner du temps.

## Fonctionnalites

- Signalement d'incidents avec photos
- Suivi des statuts (en attente, en cours, resolu)
- Console admin pour tri, mise a jour et suppression
- Reponses suggerees par IA pour le locataire
- Digest hebdomadaire genere par IA
- Export PDF des incidents

## Stack technique

- Laravel (backend)
- Blade + Tailwind CSS (UI)
- Vite (assets)
- DomPDF (export PDF)
- Groq API (IA)

## Architecture (survol)

- Controllers: `app/Http/Controllers`
- Vues: `resources/views`
- Routes: `routes/web.php`
- Models: `app/Models`
- Stockage des images: `storage/app/public` (via `php artisan storage:link`)

## Installation locale

1. Installer les dependances :

```bash
composer install
npm install
```

2. Configurer l'environnement :

```bash
cp .env.example .env
php artisan key:generate
```

3. Configurer la base de donnees dans `.env`, puis migrer :

```bash
php artisan migrate
```

4. Lier le stockage public :

```bash
php artisan storage:link
```

## Configuration

Variables d'environnement principales :

- `DB_*` : configuration base de donnees
- `GROQ_API_KEY` : cle API pour les fonctionnalites IA
- `APP_URL` : URL de l'application

## Lancement

```bash
npm run dev
php artisan serve
```

## Usage (guide rapide)

1. Creer un compte (resident).
2. Signaler un incident (titre, description, photo, localisation).
3. Suivre l'etat depuis le dashboard.
4. Connecter un compte admin pour acceder a la console `/admin`.

## IA (reponse + digest)

- Reponse IA: dans la console admin, bouton "Proposer Reponse IA".
- Digest IA: bouton "Generer le resumé " sur la liste des incidents admin.

## Export PDF

- Export global: depuis la console admin (bouton export).
- Rapport individuel: bouton rapport sur chaque incident.

## Structure des routes

Principales routes (voir `routes/web.php`) :

- `/dashboard` : dashboard utilisateur
- `/incidents/create` : creation incident
- `/admin/incidents` : console admin incidents
- `/admin/users` : gestion utilisateurs
- `/ai/chat` : chat IA (utilisateur)

## Tests

```bash
php artisan test
```

## Deploiement (notes)

- Configurer `APP_URL`, base de donnees, stockage public
- Executer migrations
- Configurer un scheduler si besoin (digest hebdo automatise)


## N.B : Connexion en tant qu'administrateur

Option 1 : Via le Terminal (Le plus rapide - "Tinker")

​Laravel possède un outil magique appelé Tinker qui permet d'interagir avec ta base de données en ligne de commande.

Lance dans ton terminal :
php artisan thinker

Tape ces lignes une par une (remplace par tes infos) :

$user = new App\Models\User();
$user->name = "Ruth Admin";
$user->email = "ruth@exemple.com";
$user->password = Hash::make('votre_mot_de_passe');
$user->role = 'admin'; 
$user->occupant_status = 'proprietaire';
$user->save();

Enfin Lance : php artisan db:seed

## Credits

Developpé par Josh Kaninda - Mars 2026
