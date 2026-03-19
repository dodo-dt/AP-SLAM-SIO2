# Projet AppResto - Lot 5 (API REST et Client-Serveur)

## Introduction
Le projet **AppResto** est une application web développée dans le cadre du module **Ateliers de Professionnalisation (AP.SLAM)** en **BTS SIO 2ème année** à l'Institut LIMAYRAC, durant l'année scolaire 2025/2026.

Ce dépôt présente le travail réalisé pour le **Lot 5**, axé sur la création d'une API REST permettant à l'application web de communiquer avec le client lourd utilisé en cuisine.

---

## Description du Besoin (Lot 5)
L'objectif de ce lot est de concevoir l'interface ("RestoWeb") permettant à l'application graphique Java des cuisines ("RestoSwing") de gérer les commandes :
* **Communication** : Échanges via des requêtes HTTP (GET) et des réponses au format JSON.
* **Sécurité & Erreurs** : Liaison interne considérée comme sécurisée (sans authentification). Les erreurs sont traitées silencieusement (renvoi d'un tableau JSON vide `[]`).
* **Actions métier** : Lister les commandes en attente, accepter une commande, refuser une commande, et terminer une commande.

---

## Livrables du Lot 5

### 1. Endpoints API (Dossier `/api`)
Création des 4 scripts PHP constituant l'API REST :
* `commandes_en_attente.php` : Renvoie la liste JSON des commandes payées mais non préparées.
* `commande_accepter.php` : Passe une commande à l'état "en préparation".
* `commande_refuser.php` : Annule ou refuse une commande.
* `commande_terminer.php` : Marque la commande comme prête à être servie.

### 2. Documentation Technique (Dossier `/doc`)
* **Documentation API** : Fichier décrivant techniquement les échanges entre le client Java et le serveur (URLs, paramètres, comportements).
* **Exemple JSON** : Modèle représentatif de la structure de données renvoyée par le serveur.

### 3. Gestion de Projet
* **Trello** : Suivi de l'avancement des tâches assignées à chaque membre du groupe (Dorian, Tesnim, Selim).
* **GitHub** : Dépôt mis à jour avec le dossier `/api`, les documents techniques et ce README.

---

## Équipe et Encadrement
* **Étudiants (Option SLAM)** : Dorian, Tesnim, Selim
* **Encadrants** : Christophe PUEL, Jean-François RAMIARA
* **Établissement** : Institut LIMAYRAC (Toulouse)
* **Année scolaire** : 2025/2026