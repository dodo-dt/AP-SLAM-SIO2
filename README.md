# 📦 Lot 5 : API REST (Client-Serveur)

## 📖 Introduction du Lot
Ce cinquième lot ouvre notre application web vers l'extérieur. L'objectif est de permettre à une application tierce (le logiciel Java "RestoSwing" utilisé par les cuisiniers) d'interagir avec notre base de données sans passer par l'interface web classique.

## 🎯 Objectifs du Lot
- Créer une API RESTful agissant comme passerelle de communication.
- Exposer des Endpoints (URLs) accessibles en HTTP GET renvoyant exclusivement des données au format JSON.
- Permettre à l'application cliente de consulter les commandes en attente, puis de modifier leur état (Accepter, Refuser, Terminer) avec une gestion d'erreur silencieuse.

## ✅ Livrables et Rendus

| Livrable Attendu (Cahier des charges) | Description des Endpoints et Fichiers | Statut |
| :--- | :--- | :---: |
| **Endpoint : En attente** | `api/commandes_en_attente.php` (Exporte la liste en JSON). | ✅ |
| **Endpoint : Accepter** | `api/commande_accepter.php` (Change l'état à "En préparation"). | ✅ |
| **Endpoint : Refuser** | `api/commande_refuser.php` (Change l'état à "Annulée"). | ✅ |
| **Endpoint : Terminer** | `api/commande_terminer.php` (Change l'état à "Prête"). | ✅ |
| **Maquette JSON** | Fichier d'exemple de la structure de données renvoyée. | ✅ |
| **Documentation technique** | Document listant les URLs et décrivant le flux JSON. | ✅ |

---
**👥 Équipe SLAM** : Dorian, Tesnim, Selim | **Encadrants** : Christophe PUEL, Jean-François RAMIARA | **Institut LIMAYRAC** (2025/2026)
