# 📦 Lot 7 : Documentation RestoSwing et Livraison

## 📖 Introduction du Lot
Ce lot est dédié à la **transmission des connaissances** pour le back-office **RestoSwing** : fournir les documents nécessaires pour **installer** et **utiliser** l’application Swing, ainsi que structurer la documentation dans le dépôt.

## 🎯 Objectifs du Lot
- Centraliser la documentation **RestoSwing** dans le dossier `/doc`.
- Rédiger un **manuel d’installation RestoSwing** (pré-requis, configuration de l’URL d’API, dépannage).
- Rédiger un **manuel d’utilisation RestoSwing** (écrans réellement disponibles : commandes en attente, détails, actions accepter/refuser/terminer).
- Publier les documents sur **GitHub** via un commit dédié.

## ✅ Livrables et Rendus

| Livrable Attendu | Description des contenus (Dossier `/doc`) | Statut |
| :--- | :--- | :---: |
| **Manuel d’installation RestoSwing** | Mise en route + configuration URL API + section Dépannage + documents associés. | ✅ |
| **Manuel d’utilisation RestoSwing** | Guide d’usage du back-office (liste commandes, détails, actions selon statut). | ✅ |
| **Livraison (GitHub)** | Documents ajoutés au dépôt dans `/doc` avec commit dédié. | ✅ |

## 🧩 Présentation de l’application RestoSwing v2

**RestoSwing v2** est un back-office Java (Swing) destiné à la gestion des commandes. Dans cette version, l’application permet :

- d’afficher la liste des **commandes en attente** (chargée via une API REST)
- d’ouvrir le **détail** d’une commande (lignes + total)
- de déclencher une action sur une commande : **accepter**, **refuser** ou **terminer** (selon le statut renvoyé par l’API)

## 📁 Contenu du dépôt (RestoSwing v2)

### Documentation (`/doc`)

- `doc/MANUEL_D_UTILISATION.md` : guide utilisateur du back-office (écrans et actions réellement disponibles).
- `doc/MANUEL_INSTALLATION.md` : prérequis, configuration de l’URL d’API et dépannage.
- `doc/LOT_7.md` : synthèse du lot 7 (ce document).

### Application Swing (sources Java)

Le code de l’application se trouve dans `RestoSwing/src/` :

| Fichier | Rôle |
|---|---|
| `RestoSwing/src/Main.java` | Point d’entrée : lance la fenêtre de liste des commandes. |
| `RestoSwing/src/Commande_liste.java` | Fenêtre principale : tableau des commandes + boutons **Rafraîchir** / **Voir détails**. |
| `RestoSwing/src/Commande_details.java` | Fenêtre de détail (dialog) : lignes de commande + total + actions **Accepter/Refuser/Terminer**. |
| `RestoSwing/src/NetworkUtils.java` | Accès API REST : récupération des commandes/détails + appels d’actions (accepter/refuser/terminer). |
| `RestoSwing/src/MyTableModel.java` | Modèle de table Swing pour la liste des commandes (colonnes ID/Type/Date/nb plat/Total TTC/Statut). |
| `RestoSwing/src/MyTableModel2.java` | Modèle de table Swing pour les lignes de commande (produit, quantité, montant HT). |
| `RestoSwing/src/Commande.java` | Modèle métier d’une commande (infos + liste de lignes + calcul du nombre de plats). |
| `RestoSwing/src/Ligne.java` | Modèle métier d’une ligne de commande (produit, quantité, montant). |

---
**👥 Équipe SLAM** : Dorian, Tesnim, Selim | **Encadrants** : Christophe PUEL, Jean-François RAMIARA | **Institut LIMAYRAC** (2025/2026)
