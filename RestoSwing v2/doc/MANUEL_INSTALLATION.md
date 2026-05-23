# Manuel d'installation (RestoSwing v2)

Ce document décrit l’installation et la mise en route du back-office **RestoSwing v2** (application Java Swing) et ses prérequis (API RestoWeb + base).

## Prérequis

- **JDK 17+** (recommandé)
- Un serveur HTTP hébergeant l’API (ex. **Apache / XAMPP**) accessible depuis la machine qui lance RestoSwing
- Une base **MySQL/MariaDB** utilisée par l’API
- La dépendance Java **org.json** (le projet utilise `import org.json.*;`)

## Configuration côté RestoSwing

- L’URL de l’API est définie dans `RestoSwing/src/NetworkUtils.java` (variable `url`).
- Si votre API n’est pas à cette adresse, modifier l’URL puis recompiler / relancer l’application.

## Lancer RestoSwing

- Ouvrir le projet dans un IDE (ex. IntelliJ) et lancer `Main` (fichier `RestoSwing/src/Main.java`), ou compiler/exécuter en ligne de commande avec les dépendances nécessaires (dont `org.json`).

---
## Dépannage
### « Connexion refusée » côté Java
- Vérifier que **MySQL** est démarré dans XAMPP (côté API).  
- Contrôler identifiants et nom de base dans la config **RestoWeb / API** (RestoSwing n’accède pas directement à MySQL).
### L’API ne répond pas
- Vérifier qu’**Apache** est actif.  
- Tester l’URL API dans le navigateur ou avec un outil type Postman.  
- Vérifier le chemin du projet dans `htdocs`.
### RestoSwing ne compile pas
- Vérifier `java -version` et `JAVA_HOME`.  
- Utiliser JDK 17+ et les bibliothèques dans `lib/` si le projet en dépend.
### Aucune commande dans le back-office
- Utiliser le **jeu de données RestoWeb** ou passer une commande depuis le site.  
- Vérifier que RestoWeb et RestoSwing pointent vers la **même base**.
---
## Documents associés
| Document | Description |
|----------|-------------|
| Manuel d’installation RestoWeb | Installation PHP / XAMPP / base (prérequis) |
| Manuel de jeu de test RestoWeb | Scénarios de test du site |
| Manuel d’utilisation RestoSwing | Utilisation du back-office (document séparé) |
---
**RestoSwing** — BTS SIO SLAM · Mise à disposition d’un service informatique complet (web + back-office).
