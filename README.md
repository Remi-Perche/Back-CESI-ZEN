# CESIZen – API Laravel

## 📘 Présentation

CESIZen est une plateforme de gestion du bien-être mental permettant aux utilisateurs de :
- Consulter des informations sur la santé mentale
- Réaliser des exercices de respiration
- Gérer leur compte utilisateur
- Accéder à un système d'authentification sécurisé

Ce projet est développé avec **Laravel**, déployé sur **AWS EC2**, et utilise **nginx + PHP-FPM**.

---

## ⚙️ Technologies

- PHP 8.3
- Laravel 10
- MySQL ou SQLite
- nginx
- Composer
- Node.js / npm

---

## 🚀 Installation

### 1. Cloner le projet

`
git clone https://github.com/Remi-Perche/Back-CESI-ZEN.git
cd Back-CESI-ZEN
`

### 2. Installer les dépendances PHP

`
composer install
`

### 3. Installer les dépendances front

`
npm install
`

### 4. Copier le fichier d'environnement

`
cp .env.example .env
`

### 5. Générer la clé d'application

`
php artisan key:generate
`

### 6. Configurer la base de données
Dans .env, modifier :

`
DB_CONNECTION=sqlite
DB_DATABASE=/database/database.sqlite
`

### 7. Exécuter les migrations

`
php artisan migrate
`

---

## Lancer le serveur en développement

`
php artisan serv
`
En production l'application est servie par nginx.

---

## Authentification
L'authentification utilise Laravel Sanctum
Pour obtenir un token :
1. `POST /api/auth/login` avec email/password
2. Récupérer le token de la réponse
3. L'utiliser dans `Authorization: Bearer <token>`

---

## API
Une collection Postman pour accéder à toutes les routes de l'API est disponible sur demande.

---

## Déploiment
Le projet est déployé automatiquement via **GitHub Actions** sur un serveur AWS EC2.

---

## Sécurité
* Authentification Sanctum
* Rôles et permissions
* Chiffrement des mots de passe
* Permissions strictes sur les fichiers

---

## Auteur

Rémi PERCHE