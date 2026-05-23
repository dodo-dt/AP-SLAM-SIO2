# Manuel d'utilisation (RestoSwing)

Ce document décrit uniquement l’utilisation **réellement disponible** dans l’application Swing fournie dans ce dépôt :

- afficher la liste des **commandes en attente**
- consulter le **détail** d’une commande
- **accepter**, **refuser** ou **terminer** une commande (selon son statut)

## Écran principal : « Commandes en attente »

Au démarrage, l’application affiche une fenêtre **Commandes en attente** contenant un tableau et deux boutons.

### Colonnes affichées

- `ID`
- `Type`
- `Date`
- `nb plat` (somme des quantités des lignes)
- `Total TTC`
- `Statut`

### Rafraîchir la liste

- Cliquer sur **Rafraîchir** pour recharger la liste des commandes.

### Voir les détails d’une commande

1. Cliquer sur une ligne du tableau.
2. Cliquer sur **Voir détails**.

Si aucune commande n’est sélectionnée, l’application affiche : « Sélectionnez une commande. ».

## Fenêtre : « Détails commande <ID> »

La fenêtre de détails affiche :

- un tableau des lignes de commande
- le **Total TTC** de la commande
- trois boutons d’action

### Colonnes des lignes

- `ID Produit`
- `Produit`
- `Quantite`
- `Montant HT` (montant unitaire HT)

### Actions possibles

- **Accepter** : possible uniquement si le `Statut` vaut exactement `En attente`.
- **Refuser** : possible uniquement si le `Statut` vaut exactement `En attente`.
- **Terminer** : possible uniquement si le `Statut` vaut exactement `En préparation`.

Après un clic sur une action, l’application affiche (« Action effectuée ! ») et la fenêtre se ferme. Pour voir le statut à jour dans la liste principale, cliquer sur **Rafraîchir** (si le statut ne change pas, l’API n’a probablement pas pris en compte l’action).

## Problèmes fréquents

- **Liste vide / erreur au chargement** : l’application dépend d’une API HTTP ; si l’API n’est pas joignable, aucune commande ne remontera (URL configurée dans `RestoSwing/src/NetworkUtils.java`).
- **Détails vides (aucune ligne)** : même cause possible ; l’application charge les lignes via l’API au moment de **Voir détails**.
- **Action refusée** : le `Statut` de la commande ne correspond pas à la condition attendue (`En attente` / `En préparation`).
