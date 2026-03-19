# Projet AppResto - Lot 3 (Développement PHP et Sessions)

## Introduction
Le projet **AppResto** est une application web développée dans le cadre du module **Ateliers de Professionnalisation (AP.SLAM)** en **BTS SIO 2ème année** à l'Institut LIMAYRAC, durant l'année scolaire 2025/2026.

Ce dépôt présente le travail de réalisation pour le **Lot 3**, axé sur la dynamisation de l'application via PHP.

---

## Description du Besoin (Lot 3)
L'objectif de ce lot est de rendre l'application fonctionnelle en gérant les données utilisateurs et produits via des scripts PHP et des sessions :
* **Authentification** : Inscription, connexion et déconnexion sécurisées.
* **Gestion des données** : Affichage dynamique des produits et gestion des commandes en base de données.
* **Persistance** : Utilisation des sessions PHP pour maintenir l'état de l'utilisateur.

---

## Livrables du Lot 3

### 1. Application Web Dynamique (Dossier `/Appresto`)
Le passage au PHP permet de gérer les fonctionnalités suivantes :
* **Authentification** : `inscription.php`, `login.php` et `disconnect.php` [cite: image_eef8ee.png].
* **Navigation & Structure** : `index.php` (accueil), `navbar.php` et `footer.php` [cite: image_eef8ee.png].
* **Processus de commande** : `menu.php`, `commande.php`, `TTC.php` et `payment.php` [cite: image_eef8ee.png].
* **Logique métier** : Dossier `functions/` contenant `db_functions.php` et `check_loggin.php` [cite: image_eef8ee.png].

### 2. Base de Données (Dossier `/sql`)
* **Script SQL** : `APPRESTO.sql` comprenant la structure des tables et un jeu de données d'essai (au moins un compte utilisateur et une commande avec plusieurs produits) [cite: 117, 118, image_eef8ee.png].

### 3. Documentation et Suivi
* **Conception** : Mise à jour des documents (MCD, MLD, MPD, IHM, DCU) pour refléter l'état final du projet [cite: 118].
* **Trello** : Suivi de l'avancement des tâches assignées à chaque membre du groupe (Dorian, Tesnim, Selim) [cite: 108, 115].

---

## Équipe et Encadrement
* **Étudiants (Option SLAM)** : Dorian, Tesnim, Selim [cite: image_efe5ac.png]
* **Encadrants** : Christophe PUEL, Jean-François RAMIARA
* **Établissement** : Institut LIMAYRAC (Toulouse)
* **Année scolaire** : 2025/2026