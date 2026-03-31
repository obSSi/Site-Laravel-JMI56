# JMI 56 - Projet BTS

<p align="center"><img src="public/images/logo-jmi56.png" width="180" alt="JMI 56"></p>

Site moderne et responsive pour un reparateur informatique (Ploermel). Le formulaire de contact alimente un espace admin pour suivre les demandes.

## Fonctionnalites
- Page publique avec sections (presentation, services, zone, contact)
- Carte Google Maps centree sur Ploermel avec rayon de 30 km (fallback iframe)
- Connexion admin simple
- Gestion des demandes : statuts (Demandes, En cours, Termine), recherche, suppression

## Stack
- Laravel 12, PHP 8.3
- MySQL (sessions + demandes)
- Vite (optionnel pour les assets)

## Installation rapide
1. Installer les dependances : `composer install`
2. Copier l'environnement : `cp .env.example .env`
3. Configurer la base MySQL dans `.env`
4. Generer la cle : `php artisan key:generate`
5. Migrer la base : `php artisan migrate`
6. Lancer le serveur : `php artisan serve`

## Admin
- URL : `/login`
- Identifiants : `admin` / `admin123`
- Acces aux onglets : Demandes, En cours, Termine
- Protection anti brute-force type fail2ban sur la connexion admin (blocage temporaire apres plusieurs erreurs)
- Reglages via `.env` :
  - `SECURITY_ADMIN_LOGIN_MAX_ATTEMPTS=5`
  - `SECURITY_ADMIN_LOGIN_LOCKOUT_SECONDS=300`

## Mentions legales et RGPD
- Pages disponibles :
  - `/mentions-legales`
  - `/politique-confidentialite`
- Pop-up d information RGPD affiche a chaque ouverture de la page d accueil.
- Parametres legaux a completer dans `.env` :
  - `LEGAL_OWNER_NAME`
  - `LEGAL_OWNER_ADDRESS`
  - `LEGAL_OWNER_PHONE_PRIMARY`
  - `LEGAL_OWNER_PHONE_SECONDARY`
  - `LEGAL_CONTACT_EMAIL`
  - `LEGAL_HOSTING_PROVIDER`
  - `LEGAL_HOSTING_ADDRESS`
  - `LEGAL_HOSTING_PHONE`

## Carte Google Maps
- Ajouter une cle dans `.env` : `GOOGLE_MAPS_KEY=...`
- Sans cle, un fallback iframe est affiche

## Assets front (optionnel)
- Dev : `npm install` puis `npm run dev`
- Build : `npm run build`
