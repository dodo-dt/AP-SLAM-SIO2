# 🍔 Projet AppResto - Application Web de Restauration

## 📖 Présentation Générale de l'Application
**AppResto** est une plateforme web complète de commande de repas en ligne, développée dans le cadre du module Ateliers de Professionnalisation (AP.SLAM). Elle permet aux clients de s'inscrire, de consulter un catalogue dynamique de produits, de passer commande (sur place ou à emporter), et de simuler un paiement. 

L'application est également dotée d'une **API REST** lui permettant de communiquer de manière transparente avec une application cliente Java ("RestoSwing") utilisée par les cuisines pour gérer l'état d'avancement des commandes. La logique métier financière (calculs HT et TTC via TVA) est gérée de manière autonome par le moteur de base de données MySQL.

---

## 📋 Le Cahier des Charges Global (Synthèse des 6 Lots)
Le développement de ce projet a été découpé en 6 lots distincts, couvrant le cycle complet de conception, réalisation et livraison d'une application professionnelle :

* **Lot 1 - Analyse et Conception :** Modélisation de la base de données (MCD, MLD), définition des parcours utilisateurs (DCU, Diagramme d'activités) et création des maquettes IHM.
* **Lot 2 - Front-End et BDD :** Intégration statique des maquettes en HTML/CSS, création du modèle physique de données (MPD) en scripts SQL, et mise en place de la gestion de projet Agile (Trello).
* **Lot 3 - PHP et Sessions :** Dynamisation du site avec PHP (PDO). Création de l'espace membre (inscription, connexion sécurisée, persistance via sessions) et affichage dynamique du catalogue.
* **Lot 4 - Triggers et Paiement :** Développement du tunnel d'achat (panier, paiement virtuel, confirmation) et automatisation complète des calculs financiers (HT/TTC) via des déclencheurs (Triggers) MySQL directement en base.
* **Lot 5 - API REST (Client-Serveur) :** Création d'une API web (réponses JSON, requêtes GET) permettant à l'application métier Java des cuisines de lister, accepter, refuser ou terminer les commandes.
* **Lot 6 - Documentation Finale :** Centralisation et rédaction des manuels techniques et utilisateurs (installation, jeux d'essai, utilisation), assurant la maintenabilité et le déploiement du projet par un tiers.

---
**👥 Équipe SLAM** : Dorian, Tesnim, Selim | **Encadrants** : Christophe PUEL, Jean-François RAMIARA
**🏫 Institut LIMAYRAC** - Année scolaire : 2025/2026
