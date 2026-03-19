--
-- Fichier 1/2 : APPRESTO_structure.sql
-- Cr�e la base de donn�es APPRESTO et sa structure (tables, index, triggers).
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cr�ation de la base de donn�es
--
CREATE DATABASE IF NOT EXISTS `APPRESTO` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `APPRESTO`;

-- --------------------------------------------------------

--
-- Structure de la table `Commande`
--

CREATE TABLE `Commande` (
  `id_commande` int(11) NOT NULL,
  `lib_commande` varchar(255) DEFAULT NULL,
  `type_commande` varchar(50) DEFAULT NULL,
  `total_TTC` decimal(10,2) DEFAULT NULL,
  `date_commande` datetime DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Etat`
--

CREATE TABLE `Etat` (
  `id_etat` int(11) NOT NULL,
  `lib_etat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `LigneCommande`
--

CREATE TABLE `LigneCommande` (
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` bigint(20) NOT NULL,
  `montant_unitaire_HT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- D�clencheurs `LigneCommande`
--
DELIMITER $$
CREATE TRIGGER `after_lignecommande_insert` AFTER INSERT ON `LigneCommande` FOR EACH ROW 
BEGIN

	DECLARE v_montant_unitaire_HT DECIMAL(10,2);
    DECLARE v_type_commande VARCHAR(255);
    DECLARE v_taux_tva DECIMAL(5,4);
    DECLARE v_prix_unitaire DECIMAL(10,2);

    SELECT type_commande
    INTO v_type_commande
    FROM Commande
    WHERE id_commande = NEW.id_commande;
    
    IF v_type_commande = "emporter" THEN
    SET v_taux_tva = 1.055;
    ELSEIF v_type_commande = "surplace" THEN
	SET v_taux_tva = 1.10;
    
    END IF;
    
    SELECT v_taux_tva * SUM(montant_unitaire_HT)
    INTO v_montant_unitaire_HT
FROM LigneCommande
    WHERE id_commande = NEW.id_commande;
    
    UPDATE Commande
    SET total_TTC = v_montant_unitaire_HT 
    WHERE id_commande = NEW.id_commande;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_lignecommande` AFTER UPDATE ON `LigneCommande` FOR EACH ROW BEGIN
    DECLARE v_total_ht_commande DECIMAL(10,2);
    DECLARE v_type_commande VARCHAR(50);
    DECLARE v_taux_tva DECIMAL(5,4);

    SELECT type_commande
    INTO v_type_commande
    FROM Commande
    WHERE id_commande = NEW.id_commande;

    IF v_type_commande = 'emporter' THEN
        SET v_taux_tva = 0.055;
    ELSE
        SET v_taux_tva = 0.10;
    END IF;

    SELECT SUM(quantite * montant_unitaire_HT)
    INTO v_total_ht_commande
    FROM LigneCommande
    WHERE id_commande = NEW.id_commande;

    UPDATE Commande
    SET total_TTC = v_total_ht_commande * (1 + v_taux_tva)
    WHERE id_commande = NEW.id_commande;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_lignecommande` BEFORE INSERT ON `LigneCommande` FOR EACH ROW BEGIN
	DECLARE v_prix_unitaire_HT decimal(5,2);
    SELECT prix_unitaire_HT INTO v_prix_unitaire_HT FROM Produit WHERE id_produit = NEW.id_produit;
	SET NEW.montant_unitaire_HT = NEW.quantite * v_prix_unitaire_HT;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_lignecommande` BEFORE UPDATE ON `LigneCommande` FOR EACH ROW BEGIN
    DECLARE v_prix_unitaire_HT DECIMAL(5,2);

    SELECT prix_unitaire_HT 
    INTO v_prix_unitaire_HT
    FROM Produit
    WHERE id_produit = NEW.id_produit;

    SET NEW.montant_unitaire_HT = NEW.quantite * v_prix_unitaire_HT;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `Produit`
--

CREATE TABLE `Produit` (
  `id_produit` int(11) NOT NULL,
  `lib_produit` varchar(100) NOT NULL,
  `prix_unitaire_HT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables d�charg�es
--

--
-- Index pour la table `Commande`
--
ALTER TABLE `Commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_commande_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_commande_etat` (`id_etat`);

--
-- Index pour la table `Etat`
--
ALTER TABLE `Etat`
  ADD PRIMARY KEY (`id_etat`);

--
-- Index pour la table `LigneCommande`
--
ALTER TABLE `LigneCommande`
  ADD PRIMARY KEY (`id_commande`,`id_produit`),
  ADD KEY `fk_lc_produit` (`id_produit`);

--
-- Index pour la table `Produit`
--
ALTER TABLE `Produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `identifiant` (`identifiant`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables d�charg�es
--

--
-- AUTO_INCREMENT pour la table `Commande`
--
ALTER TABLE `Commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; -- R�initialis� � 1 pour l'insertion de donn�es

--
-- AUTO_INCREMENT pour la table `Etat`
--
ALTER TABLE `Etat`
  MODIFY `id_etat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `Produit`
--
ALTER TABLE `Produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `Utilisateur`
  --
ALTER TABLE `Utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables d�charg�es
--

--
-- Contraintes pour la table `Commande`
--
ALTER TABLE `Commande`
  ADD CONSTRAINT `fk_commande_etat` FOREIGN KEY (`id_etat`) REFERENCES `Etat` (`id_etat`),
  ADD CONSTRAINT `fk_commande_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `Utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `LigneCommande`
--
ALTER TABLE `LigneCommande`
  ADD CONSTRAINT `fk_lc_commande` FOREIGN KEY (`id_commande`) REFERENCES `Commande` (`id_commande`),
  ADD CONSTRAINT `fk_lc_produit` FOREIGN KEY (`id_produit`) REFERENCES `Produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;