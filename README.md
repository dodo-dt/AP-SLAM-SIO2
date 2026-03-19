# AppResto - Le Palais des Saveurs

## Description
AppResto est une application web de restauration africaine permettant aux utilisateurs de s'inscrire, de consulter un menu dynamique, de passer commande et d'effectuer un paiement sécurisé. Ce projet a été réalisé dans le cadre du module AP.SLAM à l'Institut LIMAYRAC.

## Fonctionnalités principales (Lot 4)
- Authentification complète : Inscription, connexion et gestion des sessions utilisateur.
- Processus de commande : Sélection des produits, gestion des quantités et choix du mode de consommation (Sur place / À emporter).
- Calculs automatisés (Triggers) : Le système calcule automatiquement les totaux HT par ligne et le total TTC de la commande via des triggers MySQL.
- Paiement et Validation : Simulation de paiement par carte bancaire et confirmation de commande.
- Interface responsive : Design moderne adapté aux mobiles et tablettes.

## Structure du projet
Appresto/
├── css/              # Feuilles de style (design et responsive)
├── doc/              # Documentation complète du projet
│   ├── conception/   # MCD, MLD, MPD, DCU, Diagramme d'activités
│   ├── realisation/  # Manuel d'installation et jeux de tests
│   └── exploitation/ # Manuel d'utilisation
├── functions/        # Logique PHP et connexion PDO (db_functions.php)
├── img/              # Images des plats et ressources graphiques
├── js/               # Scripts JavaScript (interactions IHM)
├── sql/              # Scripts SQL de structure et de données
└── README.md         # Présentation générale du projet

## Documentation & Livrables
Conformément au cahier des charges du Lot 4, les livrables suivants sont inclus :

### Conception & Technique
- Triggers SQL : before_ligne_insert, before_ligne_update, after_ligne_insert et after_ligne_update pour l'automatisation des calculs.
- Modélisation : MCD, MLD et MPD à jour avec la gestion des taux de TVA (5,5% et 10%).
- Diagrammes : Cas d'utilisation (DCU) et Diagramme d'activités du processus de commande.

### Manuels
- Installation : Procédure de déploiement (Apache/MySQL).
- Jeu de test : Scénarios de test incluant au moins un compte client et une commande multi-produits.
- Utilisation : Guide pour l'utilisateur final.

## Démarrage rapide

### Installation
1. Cloner le projet dans votre dossier htdocs (XAMPP) ou www (WAMP).
2. Importer la base de données via phpMyAdmin en utilisant les fichiers dans /sql :
   - APPRESTO_structure_MLD.sql (Structure + Triggers)
   - APPRESTO_Data_MPD.sql (Données de test)
3. Configurer les accès à la base de données dans functions/db_functions.php.

### Accès
Ouvrir votre navigateur à l'adresse : http://localhost/Appresto

## Comptes de test
- admin (Mot de passe: admin) : Administrateur
- client_test (Mot de passe: password) : Client standard

## Technologies utilisées
- Backend : PHP 7.4+ (Architecture orientée fonctions/sessions)
- Base de données : MySQL (Triggers & Intégrité référentielle)
- Frontend : HTML5, CSS3, JavaScript
- Gestion de projet : Trello & Git/Github

## Auteurs
BTS SIO 2ème année 25.26 – AP.SLAM Institut LIMAYRAC
- Dorian, Tesnim, Selim
- Encadrants : Christophe PUEL, Jean-François RAMIARA

---
Version : 1.0  
Date : Novembre 2025  
Projet : AppResto - Lot 4 (Finalisation & Documentation)