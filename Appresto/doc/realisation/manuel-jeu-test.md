# 🧪 Manuel de Jeu de Test - AppResto

**Version** : 1.0  
**Date** : Novembre 2025  
**Application** : AppResto - Le Palais des Saveurs

---

## 📋 Table des matières

1. [Introduction](#introduction)
2. [Comptes de test](#comptes-de-test)
3. [Données de test](#données-de-test)
4. [Scénarios de test](#scénarios-de-test)
5. [Résultats attendus](#résultats-attendus)

---

## 📖 Introduction

Ce document décrit l'ensemble des comptes utilisateurs et des données de test disponibles dans l'application AppResto. Ces données sont installées automatiquement lors de l'exécution des scripts SQL.

### Objectif

Ce jeu de test permet de :
- ✅ Valider le bon fonctionnement de l'application
- ✅ Tester tous les cas d'usage (inscription, connexion, commande, paiement)
- ✅ Vérifier l'intégrité de la base de données
- ✅ Démontrer les fonctionnalités de l'application

---

## 👤 Comptes de test

### Compte Administrateur

| Champ | Valeur |
|-------|--------|
| **Identifiant** | `admin` |
| **Mot de passe** | `admin` |
| **Email** | `admin@gmail.com` |
| **ID** | `1` |
| **Type** | Administrateur |

**🔒 Sécurité** : Ce compte est prévu uniquement pour les tests. En production, changez le mot de passe !

### Création de comptes utilisateurs supplémentaires

Pour tester la fonctionnalité d'inscription, vous pouvez créer de nouveaux comptes via :
- **URL** : `http://localhost/Appresto/register.php`
- **Champs requis** :
  - Identifiant (unique)
  - Email (unique)
  - Mot de passe
  - Confirmation du mot de passe

**Exemple de comptes à créer pour les tests :**

| Identifiant | Email | Mot de passe | Usage |
|-------------|-------|--------------|-------|
| `testuser1` | `test1@example.com` | `Test123!` | Utilisateur standard |
| `testuser2` | `test2@example.com` | `Test123!` | Commandes multiples |
| `clientvip` | `vip@example.com` | `VIP123!` | Client régulier |

---

## 📊 Données de test

### 1. États des commandes

La table `Etat` contient 8 états prédéfinis pour gérer le cycle de vie des commandes :

| ID | Libellé | Description | Usage |
|----|---------|-------------|-------|
| 1 | Initialisée | Commande créée mais non finalisée | Panier en cours |
| 2 | Finalisée | Commande validée par le client | Prête pour paiement |
| 3 | Calculée | Total TTC calculé | Avant paiement |
| 4 | En attente | En attente de confirmation | Paiement en cours |
| 5 | Abandonnée | Commande annulée | Client a abandonné |
| 6 | En préparation | Cuisine en cours | Après paiement validé |
| 7 | Prête | Plat prêt à servir | En attente de service |
| 8 | Servie | Commande livrée au client | Terminée |

### 2. Produits du menu

La table `Produit` contient 12 spécialités africaines :

#### 🥗 Entrées (2 produits)

| ID | Nom | Prix HT | Ingrédients |
|----|-----|---------|-------------|
| 11 | Kachumbari | 9.99 € | Coriande, oignon, citron, chili, tomates |
| 12 | Soupe d'arachide | 8.99 € | Pâte d'arachide, oignons, carottes, patates douces |

#### 🍽️ Plats principaux (7 produits)

| ID | Nom | Prix HT | Description |
|----|-----|---------|-------------|
| 1 | Mafé | 19.99 € | Piments, patates douces, oignons, poulet |
| 2 | Attieké | 12.99 € | Attieke, concombres, tomates, oignons |
| 3 | Foutou | 15.99 € | Bananes plantain, manioc, farine de manioc |
| 4 | Poulet Yassa | 22.99 € | Poulet, oignons, ail, citrons, piments |
| 5 | Alloco Poulet | 17.99 € | Poulet, aubergine, gingembre, bananes plantains |
| 6 | Riz tikka massala | 14.99 € | Oignons, tomates, gingembre, yaourt, citron |
| 7 | Tiep Poisson | 15.99 € | Riz, oignons, carottes, aubergine, poisson |

#### 🍨 Desserts et Boissons (3 produits)

| ID | Nom | Prix HT | Description |
|----|-----|---------|-------------|
| 8 | Bissap | 8.99 € | Boisson à l'hibiscus |
| 9 | Thiakry | 9.99 € | Semoule, noix de coco, raisins secs, mangue |
| 10 | Malva Pudding | 8.99 € | Pudding sud-africain |

**📝 Note** : Les prix sont en HT (Hors Taxes). Le calcul TTC est effectué automatiquement.

### 3. Exemples de commandes

Aucune commande n'est créée par défaut. Les commandes seront générées lors des tests utilisateurs.

---

## 🧪 Scénarios de test

### Scénario 1 : Inscription d'un nouvel utilisateur

**Objectif** : Valider le processus d'inscription

**Étapes** :
1. Accéder à `http://localhost/Appresto/register.php`
2. Remplir le formulaire :
   - Identifiant : `testuser1`
   - Email : `test1@example.com`
   - Mot de passe : `Test123!`
   - Confirmation : `Test123!`
3. Cliquer sur **S'inscrire**

**Résultat attendu** :
- ✅ Redirection vers `login.php` avec message de succès
- ✅ Utilisateur créé dans la table `Utilisateur`
- ✅ Mot de passe hashé dans la base de données

**Cas d'erreur à tester** :
- ❌ Identifiant déjà existant → Message d'erreur
- ❌ Email déjà existant → Message d'erreur
- ❌ Mots de passe différents → Message d'erreur

---

### Scénario 2 : Connexion avec le compte admin

**Objectif** : Valider l'authentification

**Étapes** :
1. Accéder à `http://localhost/Appresto/login.php`
2. Remplir le formulaire :
   - Identifiant : `admin`
   - Mot de passe : `admin`
3. Cliquer sur **Se connecter**

**Résultat attendu** :
- ✅ Session créée avec `$_SESSION['identifiant'] = 'admin'`
- ✅ Redirection vers `index.php`
- ✅ Bouton "Se déconnecter" visible dans le menu
- ✅ Nom d'utilisateur affiché dans la navbar

**Cas d'erreur à tester** :
- ❌ Mauvais identifiant → Message d'erreur
- ❌ Mauvais mot de passe → Message d'erreur

---

### Scénario 3 : Consultation du menu

**Objectif** : Vérifier l'affichage des produits

**Étapes** :
1. Se connecter avec `admin/admin`
2. Cliquer sur **Menu** dans la navbar
3. Observer la page

**Résultat attendu** :
- ✅ Affichage de 12 produits
- ✅ 2 entrées visibles
- ✅ 7 plats principaux visibles
- ✅ 3 desserts/boissons visibles
- ✅ Prix affichés correctement
- ✅ Images des plats affichées

---

### Scénario 4 : Création d'une commande simple

**Objectif** : Passer une commande avec un seul produit

**Prérequis** : Être connecté

**Étapes** :
1. Accéder à `http://localhost/Appresto/commande.php`
2. Sélectionner un produit : **Mafé** (19.99 €)
3. Quantité : 1
4. Type de commande : **Sur place**
5. Cliquer sur **Valider la commande**

**Résultat attendu** :
- ✅ Commande créée dans la table `Commande`
- ✅ État de la commande : "Initialisée" (id_etat = 1)
- ✅ Ligne de commande créée dans `Ligne_Commande`
- ✅ Calcul du total TTC correct
- ✅ Redirection vers la page de paiement

**Calcul attendu** :
- Prix HT : 19.99 €
- TVA 10% : 2.00 €
- **Total TTC : 21.99 €**

---

### Scénario 5 : Commande multiple

**Objectif** : Passer une commande avec plusieurs produits

**Étapes** :
1. Ajouter au panier :
   - Mafé x2 (19.99 € chacun)
   - Bissap x1 (8.99 €)
   - Thiakry x1 (9.99 €)
2. Type : **À emporter**
3. Valider

**Résultat attendu** :
- ✅ 1 commande créée
- ✅ 3 lignes de commande créées
- ✅ Total TTC calculé : (19.99×2 + 8.99 + 9.99) × 1.10 = **64.86 €**

---

### Scénario 6 : Paiement d'une commande

**Objectif** : Valider le processus de paiement

**Prérequis** : Avoir une commande en cours

**Étapes** :
1. Sur la page de paiement (`payment.php`)
2. Remplir les informations de carte :
   - Numéro : `4111 1111 1111 1111` (carte de test)
   - Expiration : `12/25`
   - CVV : `123`
   - Nom : `TEST USER`
3. Cliquer sur **Payer**

**Résultat attendu** :
- ✅ Paiement traité par `process_payment.php`
- ✅ État de la commande mis à jour : "En préparation" (id_etat = 6)
- ✅ Message de confirmation affiché
- ✅ Possibilité de télécharger la facture

---

### Scénario 7 : Déconnexion

**Objectif** : Valider la déconnexion

**Étapes** :
1. Cliquer sur **Se déconnecter**

**Résultat attendu** :
- ✅ Session détruite
- ✅ Redirection vers `index.php`
- ✅ Boutons "Se connecter" et "S'inscrire" visibles
- ✅ Impossible d'accéder aux pages protégées

---

## ✅ Résultats attendus

### Checklist de validation

#### Base de données
- [ ] Table `Etat` : 8 enregistrements
- [ ] Table `Produit` : 12 enregistrements
- [ ] Table `Utilisateur` : Au moins 1 enregistrement (admin)
- [ ] Clés étrangères fonctionnelles
- [ ] Triggers actifs (si configurés)

#### Authentification
- [ ] Inscription fonctionnelle
- [ ] Connexion admin OK
- [ ] Déconnexion OK
- [ ] Sessions gérées correctement
- [ ] Protection des pages sécurisées

#### Fonctionnalités métier
- [ ] Affichage du menu complet
- [ ] Création de commande simple
- [ ] Création de commande multiple
- [ ] Calcul TTC correct
- [ ] Paiement fonctionnel
- [ ] Historique des commandes

#### Interface utilisateur
- [ ] Page d'accueil avec carrousel
- [ ] Menu responsive
- [ ] Navbar avec état de connexion
- [ ] Messages d'erreur clairs
- [ ] Design cohérent

---

## 📝 Rapports de tests

### Format de rapport

Pour chaque scénario testé, documenter :

| Scénario | Date | Testeur | Résultat | Commentaires |
|----------|------|---------|----------|--------------|
| Scénario 1 | [Date] | [Nom] | ✅ / ❌ | [Notes] |
| Scénario 2 | [Date] | [Nom] | ✅ / ❌ | [Notes] |

### Exemple de rapport

| Scénario | Date | Testeur | Résultat | Commentaires |
|----------|------|---------|----------|--------------|
| Connexion admin | 20/11/2025 | Test | ✅ | Fonctionne parfaitement |
| Commande simple | 20/11/2025 | Test | ✅ | Total TTC correct |
| Paiement | 20/11/2025 | Test | ⚠️ | CVV non validé côté serveur |

---

## 🔧 Réinitialisation des données de test

Pour réinitialiser la base de données et revenir aux données initiales :

```sql
-- Supprimer toutes les commandes et lignes de commandes
DELETE FROM Ligne_Commande;
DELETE FROM Commande;

-- Supprimer tous les utilisateurs sauf admin
DELETE FROM Utilisateur WHERE id_utilisateur > 1;

-- Réinitialiser les auto-incréments
ALTER TABLE Commande AUTO_INCREMENT = 1;
ALTER TABLE Ligne_Commande AUTO_INCREMENT = 1;
ALTER TABLE Utilisateur AUTO_INCREMENT = 2;
```

**⚠️ Attention** : Cette opération supprime toutes les données de test créées !

---

## 📞 Support

En cas de problème avec les données de test :

1. Vérifiez que les scripts SQL ont été exécutés correctement
2. Consultez le [Manuel d'installation](manuel-installation.md)
3. Vérifiez les logs d'erreurs PHP et MySQL

---

## 📊 Données de référence

### Formules de calcul

**TVA** : 10% sur tous les produits  
**Total TTC** = Prix HT × 1.10

**Exemples** :
- Mafé (19.99 €) → 21.99 € TTC
- Bissap (8.99 €) → 9.89 € TTC
- Commande mixte : (19.99 + 8.99) × 1.10 = 31.88 € TTC

---

**Tests validés ! ✅**

Pour utiliser l'application, consultez le [Manuel d'utilisation](../exploitation/manuel-utilisateur.md).
