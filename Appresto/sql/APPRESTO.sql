-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 02 oct. 2025 à 08:54
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `APPRESTO`
--

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

--
-- Déchargement des données de la table `Commande`
--

INSERT INTO `Commande` (`id_commande`, `lib_commande`, `type_commande`, `total_TTC`, `date_commande`, `id_utilisateur`, `id_etat`) VALUES
(1, 'Mafé, À emporter', 'À emporter', 63.27, '2025-09-18 12:30:00', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Etat`
--

CREATE TABLE `Etat` (
  `id_etat` int(11) NOT NULL,
  `lib_etat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Etat`
--

INSERT INTO `Etat` (`id_etat`, `lib_etat`) VALUES
(1, 'Initialisée'),
(2, 'Finalisée'),
(3, 'Calculée'),
(4, 'En attente'),
(5, 'Abandonnée'),
(6, 'En préparation'),
(7, 'Prête'),
(8, 'Servie');

-- --------------------------------------------------------

--
-- Structure de la table `LigneCommande`
--

CREATE TABLE `LigneCommande` (
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` bigint(20) NOT NULL,
  `prix_unitaire_HT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `LigneCommande`
--

INSERT INTO `LigneCommande` (`id_commande`, `id_produit`, `quantite`, `prix_unitaire_HT`) VALUES
(1, 1, 3, 19.99);

-- --------------------------------------------------------

--
-- Structure de la table `Produit`
--

CREATE TABLE `Produit` (
  `id_produit` int(11) NOT NULL,
  `lib_produit` varchar(100) NOT NULL,
  `prix_unitaire_HT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Produit`
--

INSERT INTO `Produit` (`id_produit`, `lib_produit`, `prix_unitaire_HT`) VALUES
(1, 'Mafé', 19.99),
(2, 'Attieké', 12.99),
(3, 'Foutou', 15.99),
(4, 'Poulet Yassa', 22.99),
(5, 'Alloco Poulet', 17.99),
(6, 'Riz tikka massala', 14.99),
(7, 'Tiep Poisson', 15.99),
(8, 'Bissap', 8.99),
(9, 'Thiakry', 9.99),
(10, 'Malva Pudding', 8.99),
(11, 'Kachumbari', 9.99),
(12, "Soupe d'arachide", 8.99);

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
-- Déchargement des données de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`id_utilisateur`, `identifiant`, `mot_de_passe`, `email`) VALUES
(1, 'Tesnim', 'Benama', 'tesnim.benama@limayrac.fr'),
(2, 'Dorian', 'Dutertre', 'dorian.dutertre@limayrac.fr'),
(3, 'Selim', 'Nouira', 'selim.nouira@limayrac.fr');

--
-- Index pour les tables déchargées
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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Commande`
--
ALTER TABLE `Commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
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
