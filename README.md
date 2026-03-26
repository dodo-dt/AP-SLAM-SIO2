# 🍔 Projet AppResto - Application Web de Restauration

## 📖 Introduction
Le projet **AppResto** est une application web développée dans le cadre du module **Ateliers de Professionnalisation (AP.SLAM)** en **BTS SIO 2ème année** à l'Institut LIMAYRAC, durant l'année scolaire 2025/2026.

L'objectif final est de proposer une application permettant à un client de commander des produits auprès d’un restaurateur, avec un suivi de commande pour les deux parties.  
*(Note : Selon l'avancement, le travail peut être exclusivement centré sur la phase de conception - Lot 1).*

---

## 🎯 Description Générale du Besoin
- 🧑‍💻 **Côté Client** : inscription, connexion, consultation des produits, choix des quantités, commande sur place ou à emporter, paiement fictif.
- 👨‍🍳 **Côté Restaurateur** : gestion des commandes (accepter, refuser, préparer).
- 🔔 **Notification** : alerte par e-mail lorsque la commande est prête pour retrait.

---

## ⚙️ Fonctionnalités Ciblées (Vision Globale du Projet)
Ces fonctionnalités ne sont pas encore toutes développées mais représentent l’objectif final :
- Gestion des utilisateurs (inscription, connexion, déconnexion).
- Affichage et sélection de produits.
- Commande avec options (sur place / à emporter).
- Calcul automatique du total TTC (avec TVA à 5,5% ou 10% selon le type de commande).
- Paiement fictif par carte bancaire.
- Gestion des commandes côté restaurateur.
- Notification e-mail au client.
- Suivi et finalisation de commande.

---

## 💶 Gestion de la TVA
- **5,5%** : commande à emporter.  
- **10%** : consommation sur place.  
- Le taux est **uniforme par commande**.  
> 🔗 *[Référence : economie.gouv.fr – TVA réduite restauration](https://www.economie.gouv.fr/cedef/tva-reduite-restauration)*

---

## 📦 Lotissement du Projet

Le projet est prévu en plusieurs lots. Voici le récapitulatif technique et les livrables attendus pour chaque étape :

| Lot | Thème de l'étape | Description & Livrables Attendus |
|:---:|:---|:---|
| **Lot 1** | **Conception Initiale** | • Diagrammes (Cas d’utilisation, Activités)<br>• Modèles de données (MCD, MLD)<br>• Interface Homme-Machine (IHM) & Sitemap |
| **Lot 2** | **Développement Initial (Statique)** | • MPD au format SQL<br>• Pages HTML/CSS statiques (préparation login/register)<br>• Lotissement Trello des tâches |
| **Lot 3** | **Développement PHP & Sessions** | • `index.php` (Accueil), `inscription.php`<br>• `connexion.php`, `deconnexion.php`<br>• `commander.php` (liste des produits)<br>• Lotissement Trello |
| **Lot 4** | **Processus de Commande** | • `commander.php` (commander ses produits)<br>• `payer.php` (payer sa commande)<br>• `confirmer.php` (confirmation de prise en compte) |
| **Lot 5** | **API REST (RestoWeb / RestoSwing)** | • Interface vers l'application Java des cuisines<br>• `commandes_en_attente.php`, `commande_accepter.php`<br>• `commande_refuser.php`, `commande_terminer.php` |
| **Lot 6** | **Documentation de l'application** | • Mise à jour MCD/MLD/MPD/IHM/Sitemap/DCU<br>• Valeurs (États, Types conso) & Maquette JSON<br>• Manuels (Installation et Jeu de test) |
| **Lot 7** | **Application  Java RestoSwing** | • Développement application Java Swing (Gestion commandes)<br>• Consommation de l’API REST RestoWeb<br>• Manipulation d'objets métiers via réponses JSON |

*(Les autres lots seront réalisés ultérieurement).*

---

## 👥 Auteurs et Contact
- **Étudiants** : Dutertre Dorian, Tesnim Benama, Selim Nouira  
- **Encadrants** : Christophe PUEL, Jean-François RAMIARA  
- **Établissement** : Institut LIMAYRAC  
- **Formation** : BTS SIO 2ème année – Option SLAM  
- **Année scolaire** : 2025/2026
