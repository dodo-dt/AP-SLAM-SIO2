# 📦 Lot 4 : Paiement et Triggers SQL

## 📖 Introduction du Lot
Cette quatrième phase se concentre sur la finalisation du parcours d'achat de l'utilisateur et sur le déport de la logique métier (les calculs financiers) vers le moteur de base de données, pour garantir l'intégrité des prix.

## 🎯 Objectifs du Lot
- Créer l'interface permettant au client de valider son panier et de simuler un paiement virtuel par carte bancaire.
- Automatiser les calculs des prix : développer des déclencheurs MySQL (Triggers) qui calculent instantanément le Total HT par ligne et le Total TTC de la commande (selon la TVA applicable) à chaque insertion ou modification, sans utiliser de PHP pour les mathématiques.

## ✅ Livrables et Rendus

| Livrable Attendu (Cahier des charges) | Fichiers PHP et Scripts SQL concernés | Statut |
| :--- | :--- | :---: |
| **Page de commande** | `commander.php` (Validation finale des produits et quantités). | ✅ |
| **Page de paiement** | `payer.php` (Formulaire de simulation de paiement CB). | ✅ |
| **Page de confirmation** | `confirmer.php` (Validation de la prise en compte en BDD). | ✅ |
| **Triggers SQL (Lignes)** | `before_ligne_insert`, `before_ligne_update` (Calcul du prix HT). | ✅ |
| **Triggers SQL (Commande)** | `after_ligne_insert`, `after_ligne_update` (Calcul du Total TTC). | ✅ |

---
**👥 Équipe SLAM** : Dorian, Tesnim, Selim | **Encadrants** : Christophe PUEL, Jean-François RAMIARA | **Institut LIMAYRAC** (2025/2026)
