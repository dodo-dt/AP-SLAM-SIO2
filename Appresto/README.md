# AppResto - Le Palais des Saveurs

## 📋 Description

**AppResto** est une application web de restauration africaine permettant aux utilisateurs de consulter un menu, passer des commandes en ligne et effectuer des paiements. L'application propose une expérience utilisateur moderne avec un design attractif inspiré de la cuisine africaine.

## 🎯 Fonctionnalités principales

- **Inscription et Connexion** : Système d'authentification sécurisé
- **Menu interactif** : Consultation des plats disponibles avec prix
- **Gestion des commandes** : Ajout au panier et validation des commandes
- **Système de paiement** : Intégration du paiement en ligne
- **Interface responsive** : Design adapté à tous les écrans

## 🗂️ Structure du projet

```
Appresto/
├── doc/                      # Documentation complète
│   ├── conception/           # Documents de conception
│   ├── realisation/          # Documents de réalisation
│   └── exploitation/         # Documents d'exploitation
├── sql/                      # Scripts de base de données
├── functions/                # Fonctions PHP
├── css/                      # Feuilles de style
├── js/                       # Scripts JavaScript
└── img/                      # Images et ressources
```

## 📚 Documentation

### Documents de conception
- [Diagramme de cas d'utilisation](doc/conception/diagramme-cas-utilisation.md)
- [MCD - Modèle Conceptuel de Données](doc/conception/mcd-modele-conceptuel.md)
- [MLD - Modèle Logique de Données](doc/conception/mld-modele-logique.md)
- [MPD - Modèle Physique de Données](doc/conception/mpd-modele-physique.md)
- [Plan du site (Sitemap)](doc/conception/sitemap.md)
- [Valeurs possibles](doc/conception/valeurs-possibles.md)

### Documents de réalisation
- [📦 Manuel d'installation](doc/realisation/manuel-installation.md)
- [🧪 Manuel de jeu de test](doc/realisation/manuel-jeu-test.md)

### Documents d'exploitation
- [📖 Manuel d'utilisation](doc/exploitation/manuel-utilisateur.md)

## 🚀 Démarrage rapide

### Prérequis
- XAMPP (ou équivalent : Apache + MySQL + PHP 7.4+)
- Navigateur web moderne
- Git (pour le clonage du dépôt)

### Installation rapide

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_DEPOT]
   cd Appresto
   ```

2. **Installer la base de données**
   - Démarrer XAMPP et lancer Apache + MySQL
   - Exécuter les scripts dans l'ordre :
     - `sql/APPRESTO_structure.sql`
     - `sql/APPRESTO_Data.sql`

3. **Configurer l'application**
   - Vérifier la configuration dans `functions/db_functions.php`
   - Adapter les paramètres de connexion si nécessaire

4. **Accéder à l'application**
   - Ouvrir : `http://localhost/Appresto`

📖 Pour des instructions détaillées, consultez le [Manuel d'installation](doc/realisation/manuel-installation.md)

## 👤 Comptes de test

| Identifiant | Mot de passe | Rôle |
|-------------|--------------|------|
| admin       | admin        | Administrateur |

Voir le [Manuel de jeu de test](doc/realisation/manuel-jeu-test.md) pour plus de détails.

## 🛠️ Technologies utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL / MariaDB
- **Frontend** : HTML5, CSS3, JavaScript
- **Serveur** : Apache (XAMPP)
- **Architecture** : MVC (Modèle-Vue-Contrôleur)

## 📞 Support et Contact

Pour toute question ou problème :
- Consulter le [Manuel d'utilisation](doc/exploitation/manuel-utilisateur.md)
- Consulter le [Manuel d'installation](doc/realisation/manuel-installation.md)

## 👨‍💻 Auteurs

**BTS SIO 2ème année 25.26 – AP.SLAM**  
Institut LIMAYRAC

## 📄 Licence

Ce projet est développé dans le cadre d'un projet pédagogique à l'Institut LIMAYRAC.

---

**Version** : 1.0  
**Date** : Novembre 2025  
**Projet** : AppResto - Lot 4 (Documentation)
