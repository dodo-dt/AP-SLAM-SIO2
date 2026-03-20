# 📦 Manuel d'Installation - AppResto

**Version** : 1.0  
**Date** : Novembre 2025  
**Application** : AppResto - Le Palais des Saveurs

---

## 📋 Table des matières

1. [Prérequis](#prérequis)
2. [Récupération du code source](#récupération-du-code-source)
3. [Installation du serveur web](#installation-du-serveur-web)
4. [Installation de la base de données](#installation-de-la-base-de-données)
5. [Configuration de l'application](#configuration-de-lapplication)
6. [Vérification de l'installation](#vérification-de-linstallation)
7. [Dépannage](#dépannage)

---

## 🔧 Prérequis

Avant de commencer l'installation, assurez-vous d'avoir les éléments suivants :

### Logiciels requis

| Logiciel | Version minimale | Téléchargement |
|----------|------------------|----------------|
| XAMPP | 8.0+ | [xampp.org](https://www.apachefriends.org/) |
| PHP | 7.4+ | Inclus dans XAMPP |
| MySQL/MariaDB | 5.7+ / 10.3+ | Inclus dans XAMPP |
| Apache | 2.4+ | Inclus dans XAMPP |

### Systèmes d'exploitation supportés
- ✅ Windows 10/11
- ✅ macOS 10.15+
- ✅ Linux (Ubuntu 20.04+, Debian 10+)

### Navigateurs supportés
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ✅ Safari 14+

---

## 📥 Récupération du code source

### Option 1 : Clonage via Git (recommandé)

```bash
# Naviguer vers le dossier htdocs de XAMPP
cd C:\xampp\htdocs

# Cloner le dépôt depuis GitHub
git clone https://github.com/dodo-dt/AP-SIO2-SLAM.git Appresto

# Se positionner dans le dossier
cd Appresto
```

### Option 2 : Téléchargement manuel

1. Télécharger l'archive ZIP depuis GitHub
2. Extraire l'archive dans `C:\xampp\htdocs\`
3. Renommer le dossier en `Appresto`

### Branche à utiliser

- **Production** : `main` ou `master`
- **Développement** : `develop`
- **Release** : Utiliser les tags (ex: `v1.0.0`)

```bash
# Pour utiliser une version spécifique
git checkout v1.0.0

# Pour utiliser la branche principale
git checkout main
```

---

## 🌐 Installation du serveur web

### Installation de XAMPP

#### Windows

1. **Télécharger XAMPP**
   - Visitez [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Téléchargez la version pour Windows

2. **Installer XAMPP**
   - Exécutez le fichier `.exe` téléchargé
   - Suivez les instructions de l'assistant
   - Installation recommandée : `C:\xampp\`

3. **Démarrer les services**
   - Ouvrez le **XAMPP Control Panel**
   - Cliquez sur **Start** pour Apache
   - Cliquez sur **Start** pour MySQL


### Vérification du serveur

Ouvrez votre navigateur et accédez à :
- `http://localhost/` → Devrait afficher la page d'accueil XAMPP
- `http://localhost/phpmyadmin/` → Devrait ouvrir phpMyAdmin

---

## 💾 Installation de la base de données

### Étape 1 : Accéder à phpMyAdmin

1. Ouvrez votre navigateur
2. Accédez à `http://localhost/phpmyadmin/`

### Étape 2 : Exécuter les scripts SQL

Les scripts doivent être exécutés **dans l'ordre suivant** :

#### Script 1 : Structure de la base de données

```sql
-- Fichier : sql/APPRESTO_structure.sql
```

**Contenu :**
- Création de la base de données `APPRESTO`
- Création des tables :
  - `Utilisateur` : Gestion des utilisateurs
  - `Commande` : Gestion des commandes
  - `Etat` : États des commandes
  - `Produit` : Catalogue de produits
  - `Ligne_Commande` : Détail des commandes
- Création des clés primaires et étrangères
- Création des index
- Création des triggers

**Exécution :**

1. Dans phpMyAdmin, cliquez sur l'onglet **SQL**
2. Copiez le contenu de `sql/APPRESTO_structure.sql`
3. Collez dans la zone de texte
4. Cliquez sur **Exécuter**
5. Vérifiez qu'il n'y a pas d'erreurs

#### Script 2 : Données initiales

```sql
-- Fichier : sql/APPRESTO_Data.sql
```

**Contenu :**
- Insertion des états de commande (Initialisée, Finalisée, etc.)
- Insertion des produits du menu (Mafé, Attieké, etc.)
- Insertion du compte administrateur par défaut

**Exécution :**

1. Dans phpMyAdmin, sélectionnez la base `APPRESTO`
2. Cliquez sur l'onglet **SQL**
3. Copiez le contenu de `sql/APPRESTO_Data.sql`
4. Collez dans la zone de texte
5. Cliquez sur **Exécuter**
6. Vérifiez qu'il n'y a pas d'erreurs

### Étape 3 : Vérification de l'installation

Vérifiez que les tables ont été créées :

1. Dans phpMyAdmin, sélectionnez la base `APPRESTO`
2. Vous devriez voir les tables suivantes :
   - ✅ `Commande`
   - ✅ `Etat`
   - ✅ `Ligne_Commande`
   - ✅ `Produit`
   - ✅ `Utilisateur`

3. Vérifiez les données insérées :
   ```sql
   SELECT * FROM Etat;           -- Devrait afficher 8 états
   SELECT * FROM Produit;        -- Devrait afficher 12 produits
   SELECT * FROM Utilisateur;    -- Devrait afficher 1 utilisateur (admin)
   ```

### Méthode alternative : Ligne de commande

Si vous préférez utiliser la ligne de commande :

```bash
# Naviguer vers le dossier des scripts SQL
cd C:\xampp\htdocs\Appresto\sql

# Exécuter le script de structure
mysql -u root -p < APPRESTO_structure.sql

# Exécuter le script de données
mysql -u root -p APPRESTO < APPRESTO_Data.sql
```

---

## ⚙️ Configuration de l'application

### Configuration de la connexion à la base de données

**Fichier** : `functions/db_functions.php`

```php
<?php

// Connexion à la BD
function db_connect() {
  $dsn = 'mysql:host=localhost;dbname=APPRESTO;charset=utf8';
  try {
    $dbh = new PDO($dsn, 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
  } catch (PDOException $ex) {
    die("Erreur lors de la connexion SQL : " . $ex->getMessage());
  }
}
```

### Paramètres à modifier (si nécessaire)

| Paramètre | Valeur par défaut | Description |
|-----------|-------------------|-------------|
| `host` | `localhost` | Serveur de base de données |
| `dbname` | `APPRESTO` | Nom de la base de données |
| `username` | `root` | Utilisateur MySQL |
| `password` | `` (vide) | Mot de passe MySQL |
| `charset` | `utf8` | Encodage des caractères |

**⚠️ Important :** Si vous avez défini un mot de passe pour l'utilisateur `root` de MySQL, modifiez la ligne :

```php
$dbh = new PDO($dsn, 'root', 'VOTRE_MOT_DE_PASSE', ...);
```

### Configuration PHP (php.ini)

Vérifiez les paramètres suivants dans `C:\xampp\php\php.ini` :

```ini
; Extensions requises (décommenter si nécessaire)
extension=pdo_mysql
extension=mysqli

; Paramètres recommandés
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M

; Affichage des erreurs (développement uniquement)
display_errors = On
error_reporting = E_ALL
```

**⚠️ Rappel :** Désactiver `display_errors` en production !

### Permissions des dossiers (Linux/macOS uniquement)

```bash
# Donner les permissions appropriées
sudo chmod -R 755 /var/www/html/Appresto
sudo chown -R www-data:www-data /var/www/html/Appresto
```

---

## ✅ Vérification de l'installation

### Test 1 : Page d'accueil

1. Ouvrez votre navigateur
2. Accédez à `http://localhost/Appresto/`
3. ✅ Vous devriez voir la page d'accueil avec le carrousel

### Test 2 : Connexion

1. Cliquez sur **Se connecter** dans la barre de navigation
2. Utilisez les identifiants de test :
   - **Identifiant** : `admin`
   - **Mot de passe** : `admin`
3. ✅ Vous devriez être connecté et redirigé vers la page d'accueil

### Test 3 : Consultation du menu

1. Cliquez sur **Menu** dans la barre de navigation
2. ✅ Vous devriez voir la liste des 12 plats africains

### Test 4 : Connexion à la base de données

1. Accédez à `http://localhost/Appresto/menu.php`
2. ✅ Les produits doivent s'afficher sans erreur
3. ❌ Si vous voyez une erreur de connexion, vérifiez `functions/db_functions.php`

### Checklist complète

- [ ] XAMPP installé et démarré
- [ ] Apache démarré
- [ ] MySQL démarré
- [ ] Base de données `APPRESTO` créée
- [ ] Tables créées (5 tables)
- [ ] Données insérées (états, produits, utilisateur admin)
- [ ] Fichier `db_functions.php` configuré
- [ ] Page d'accueil accessible
- [ ] Connexion fonctionnelle
- [ ] Menu affiché correctement

---

## 🔧 Dépannage

### Problème : "Cannot connect to database"

**Cause** : Mauvaise configuration de la connexion

**Solution** :
1. Vérifiez que MySQL est démarré dans XAMPP Control Panel
2. Vérifiez les paramètres dans `functions/db_functions.php`
3. Testez la connexion dans phpMyAdmin

### Problème : "Page not found" (404)

**Cause** : Mauvais chemin d'installation

**Solution** :
1. Vérifiez que l'application est dans `C:\xampp\htdocs\Appresto\`
2. Accédez à `http://localhost/Appresto/` (avec le A majuscule)
3. Vérifiez que Apache est démarré

### Problème : Page blanche sans erreur

**Cause** : Erreurs PHP masquées

**Solution** :
1. Activez l'affichage des erreurs dans `php.ini`
2. Ajoutez en haut de `index.php` :
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
3. Rechargez la page et lisez le message d'erreur

### Problème : "Access denied for user 'root'@'localhost'"

**Cause** : Mot de passe MySQL incorrect

**Solution** :
1. Réinitialisez le mot de passe MySQL dans XAMPP
2. Ou modifiez `db_functions.php` avec le bon mot de passe

### Problème : Caractères accentués mal affichés

**Cause** : Problème d'encodage

**Solution** :
1. Vérifiez que la base de données utilise `utf8mb4`
2. Vérifiez que les fichiers PHP sont encodés en UTF-8
3. Ajoutez `<meta charset="UTF-8">` dans les pages HTML

### Problème : Port 80 ou 3306 déjà utilisé

**Cause** : Un autre service utilise ces ports

**Solution Windows** :
```powershell
# Vérifier quel programme utilise le port 80
netstat -ano | findstr :80

# Arrêter le service (ex: IIS, Skype)
net stop W3SVC
```

**Solution alternative** : Changer les ports dans XAMPP Config

## 📝 Notes importantes

- ⚠️ **Sécurité** : Le compte `admin/admin` est un compte de test. Changez le mot de passe en production !
- ⚠️ **Encodage** : Tous les fichiers doivent être encodés en UTF-8

---

**Installation terminée avec succès ! 🎉**

Vous pouvez maintenant consulter le [Manuel d'utilisation](../exploitation/manuel-utilisateur.md) pour découvrir les fonctionnalités de l'application.
