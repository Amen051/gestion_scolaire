-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 08 juil. 2025 à 19:15
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_scolaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `nom`, `email`, `mot_de_passe`, `created_at`) VALUES
(1, 'KOUDJODJI Kwami', 'koudjodji@gmail.com', '$2y$10$TYsvhaXAn.DEo/ds3rfzPuJHKJoTlBO5FnjtCvzNO4wrdCa/HK.qS', '2025-07-05 16:10:14');

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_classe` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `nom_classe`, `created_at`) VALUES
(1, '6eme', '2025-07-06 12:38:41'),
(2, '5eme', '2025-07-06 12:39:30'),
(3, '4eme', '2025-07-06 12:39:54'),
(4, '3eme', '2025-07-06 12:40:14');

-- --------------------------------------------------------

--
-- Structure de la table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
CREATE TABLE IF NOT EXISTS `eleves` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `email_parent` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_parent` int DEFAULT NULL,
  `ecolage` int NOT NULL,
  `classe` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `eleves`
--

INSERT INTO `eleves` (`id`, `nom`, `prenom`, `date_naissance`, `sexe`, `created_at`, `email_parent`, `contact_parent`, `ecolage`, `classe`) VALUES
(1, 'ADHOBOU', 'Justin Dieu Béni', '2013-02-21', 'M', '2025-07-05 21:59:44', 'amedomeattiogbe@gmail.com', 97663311, 34000, '6ème'),
(2, 'AGBOKOU', 'Jean Paul', '2014-06-24', 'M', '2025-07-05 22:04:01', 'bernadetteattitso3@gmail.com', 97445533, 30000, '5eme'),
(3, 'ACHIKANGORO', 'Judith', '2015-11-14', 'F', '2025-07-05 22:08:40', 'elomattiogbe05@gmail.com', 70001100, 50000, '4eme'),
(4, 'AMENOUGLO', 'Gil Francesco', '2016-11-14', 'M', '2025-07-06 18:04:01', 'elomattiogbe05@gmail.com', 90119922, 21900, '6eme'),
(5, 'ADABADJI', 'Djobocou Israel', '2013-07-21', 'M', '2025-07-06 18:05:03', 'attiogbegodwin2002@gmail.com', 76557733, 51000, '4eme'),
(6, 'DOSSOU', 'Jean Lucien Folly', '2014-12-17', 'M', '2025-07-06 18:06:14', 'amenattiogbe05@gmail.com', 77661199, 52000, '5eme'),
(7, 'DJIKPO', 'Brigitte Noeline', '2011-12-25', 'F', '2025-07-06 18:07:14', 'mensahattiogbe05@gmail.com', 78992211, 45000, '3eme'),
(8, 'ADJABACLOU', 'Mimi', '2012-10-27', 'F', '2025-07-06 18:08:11', 'brigitte@gmail.com', 98332211, 25000, '4eme'),
(9, 'JOUFLOU', 'Amavi Gisèle', '2011-08-09', 'F', '2025-07-06 18:09:13', 'gisele@gmail.com', 93221198, 54000, '3eme'),
(10, 'ABALO', 'Kokou Marcelin', '2012-02-04', 'M', '2025-07-06 18:10:06', 'abalo@gmail.com', 71111111, 40000, '4eme'),
(11, 'ABALO', 'Christianne', '2012-05-08', 'F', '2025-07-06 18:11:06', 'abalo@gmail.com', 71111111, 40000, '4eme'),
(12, 'ZAMAKOU', 'Voindi voindi', '2013-03-15', 'M', '2025-07-06 18:12:05', 'zamakou@gmail.com', 90557788, 35000, '5eme');

-- --------------------------------------------------------

--
-- Structure de la table `enseignement`
--

DROP TABLE IF EXISTS `enseignement`;
CREATE TABLE IF NOT EXISTS `enseignement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prof_id` int DEFAULT NULL,
  `matiere` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `classe` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jour` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prof_id` (`prof_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignement`
--

INSERT INTO `enseignement` (`id`, `prof_id`, `matiere`, `classe`, `jour`, `heure_debut`, `heure_fin`) VALUES
(1, 1, 'Français', '3eme', 'Lundi', '07:00:00', '07:55:00'),
(5, 3, 'PCT', '6eme', 'Mardi', '07:00:00', '07:55:00'),
(2, 2, 'ANGLAIS', '6eme', 'Lundi', '07:55:00', '08:50:00'),
(3, 2, 'ANGLAIS', '5eme', 'Lundi', '08:50:00', '09:45:00'),
(4, 2, 'ANGLAIS', '4eme', 'Lundi', '10:00:00', '10:55:00'),
(6, 4, 'MATHEMATIQUES', '4eme', 'Mercredi', '07:55:00', '08:50:00'),
(7, 4, 'MATHEMATIQUES', '6eme', 'Vendredi', '10:00:00', '10:55:00'),
(8, 4, 'MATHEMATIQUES', '5eme', 'Jeudi', '08:50:00', '09:45:00'),
(9, 4, 'MATHEMATIQUES', '3eme', 'Lundi', '10:00:00', '10:55:00'),
(10, 1, 'FRANCAIS', '6eme', 'Mardi', '07:55:00', '08:50:00'),
(11, 1, 'FRANCAIS', '5eme', 'Mercredi', '07:00:00', '07:55:00'),
(12, 1, 'FRANCAIS', '4eme', 'Jeudi', '07:00:00', '07:55:00'),
(13, 2, 'ANGLAIS', '3eme', 'Vendredi', '07:00:00', '07:55:00'),
(14, 3, 'PCT', '5eme', 'Mardi', '08:50:00', '09:45:00'),
(15, 3, 'PCT', '4eme', 'Mercredi', '08:50:00', '09:45:00'),
(16, 3, 'PCT', '3eme', 'Mercredi', '07:00:00', '07:55:00'),
(17, 5, 'ETUDE BIBLIQUE', '6eme', 'Lundi', '07:55:00', '08:50:00'),
(18, 5, 'ETUDE BIBLIQUE', '5eme', 'Mardi', '11:50:00', '12:45:00'),
(19, 5, 'ETUDE BIBLIQUE', '4eme', 'Vendredi', '11:50:00', '12:45:00'),
(20, 5, 'ETUDE BIBLIQUE', '3eme', 'Jeudi', '08:50:00', '09:45:00'),
(21, 7, 'SPORT', '6eme', 'Mardi', '14:00:00', '15:00:00'),
(22, 7, 'SPORT', '5eme', 'Mardi', '15:00:00', '16:00:00'),
(23, 7, 'SPORT', '4eme', 'Mardi', '16:00:00', '17:00:00'),
(24, 7, 'SPORT', '3eme', 'Lundi', '14:00:00', '15:00:00'),
(25, 6, 'ECM', '6eme', 'Lundi', '10:55:00', '11:50:00'),
(26, 6, 'ECM', '5eme', 'Mardi', '10:55:00', '11:50:00'),
(27, 6, 'ECM', '4eme', 'Mercredi', '10:55:00', '11:50:00'),
(28, 6, 'ECM', '3eme', 'Jeudi', '10:55:00', '11:50:00'),
(29, 1, 'HISTO-GEO', '6eme', 'Vendredi', '14:00:00', '15:00:00'),
(30, 1, 'HISTO-GEO', '5eme', 'Mardi', '14:00:00', '15:00:00'),
(31, 1, 'HISTO-GEO', '4eme', 'Mercredi', '16:00:00', '17:00:00'),
(32, 1, 'HISTO-GEO', '3eme', 'Jeudi', '14:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_matiere` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom_matiere`, `created_at`) VALUES
(1, 'ANGLAIS', '2025-07-06 13:21:37'),
(2, 'FRANCAIS', '2025-07-06 13:21:37'),
(3, 'MATHEMATIQUES', '2025-07-06 13:21:37'),
(4, 'HISTO-GEO', '2025-07-06 13:21:37'),
(5, 'ECM', '2025-07-06 13:21:37'),
(6, 'PCT', '2025-07-06 13:21:37'),
(7, 'SPORT', '2025-07-06 13:21:37'),
(8, 'ETUDE BIBLIQUE', '2025-07-08 10:16:13');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eleve_id` int NOT NULL,
  `matiere` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `trimestre` enum('Trimestre 1','Trimestre 2','Trimestre 3') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `note_devoir` float NOT NULL,
  `date_saisie` datetime DEFAULT CURRENT_TIMESTAMP,
  `professeur_id` int NOT NULL,
  `classe` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `coefficient` int NOT NULL,
  `note_composition` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eleve_id` (`eleve_id`),
  KEY `enseignement_id` (`matiere`(250)),
  KEY `fk` (`classe`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id`, `eleve_id`, `matiere`, `trimestre`, `note_devoir`, `date_saisie`, `professeur_id`, `classe`, `coefficient`, `note_composition`) VALUES
(1, 1, 'ANGLAIS', 'Trimestre 1', 19, '2025-07-06 13:24:24', 2, '6eme', 2, 16),
(2, 2, 'ANGLAIS', 'Trimestre 1', 15, '2025-07-06 18:20:43', 2, '5eme', 2, 17),
(3, 6, 'ANGLAIS', 'Trimestre 1', 17, '2025-07-06 18:20:43', 2, '5eme', 2, 19),
(4, 12, 'ANGLAIS', 'Trimestre 1', 13, '2025-07-06 18:20:43', 2, '5eme', 2, 16),
(5, 4, 'ANGLAIS', 'Trimestre 1', 17, '2025-07-06 20:42:52', 2, '6eme', 2, 16),
(6, 1, 'PCT', 'Trimestre 1', 12, '2025-07-07 14:06:39', 3, '6eme', 2, 14),
(7, 4, 'PCT', 'Trimestre 1', 14, '2025-07-07 14:06:39', 3, '6eme', 2, 16),
(8, 1, 'ETUDE BIBLIQUE', 'Trimestre 1', 17, '2025-07-08 10:26:34', 5, '6eme', 1, 16),
(9, 4, 'ETUDE BIBLIQUE', 'Trimestre 1', 16, '2025-07-08 10:26:34', 5, '6eme', 1, 17),
(10, 2, 'ETUDE BIBLIQUE', 'Trimestre 1', 18, '2025-07-08 10:26:56', 5, '5eme', 1, 18),
(11, 6, 'ETUDE BIBLIQUE', 'Trimestre 1', 19, '2025-07-08 10:26:56', 5, '5eme', 1, 17),
(12, 12, 'ETUDE BIBLIQUE', 'Trimestre 1', 20, '2025-07-08 10:26:56', 5, '5eme', 1, 17),
(13, 3, 'ETUDE BIBLIQUE', 'Trimestre 1', 16, '2025-07-08 10:27:29', 5, '4eme', 1, 16),
(14, 5, 'ETUDE BIBLIQUE', 'Trimestre 1', 17, '2025-07-08 10:27:29', 5, '4eme', 1, 12),
(15, 8, 'ETUDE BIBLIQUE', 'Trimestre 1', 13, '2025-07-08 10:27:29', 5, '4eme', 1, 14),
(16, 10, 'ETUDE BIBLIQUE', 'Trimestre 1', 19, '2025-07-08 10:27:29', 5, '4eme', 1, 17),
(17, 11, 'ETUDE BIBLIQUE', 'Trimestre 1', 18, '2025-07-08 10:27:29', 5, '4eme', 1, 15),
(18, 7, 'ETUDE BIBLIQUE', 'Trimestre 1', 17, '2025-07-08 10:27:44', 5, '3eme', 1, 19),
(19, 9, 'ETUDE BIBLIQUE', 'Trimestre 1', 16, '2025-07-08 10:27:44', 5, '3eme', 1, 18),
(20, 1, 'FRANCAIS', 'Trimestre 1', 15, '2025-07-08 16:44:44', 1, '6eme', 3, 18),
(21, 4, 'FRANCAIS', 'Trimestre 1', 17, '2025-07-08 16:44:44', 1, '6eme', 3, 17),
(22, 1, 'HISTO-GEO', 'Trimestre 1', 14, '2025-07-08 16:45:05', 1, '6eme', 2, 17),
(23, 4, 'HISTO-GEO', 'Trimestre 1', 13, '2025-07-08 16:45:05', 1, '6eme', 2, 19),
(24, 1, 'SPORT', 'Trimestre 1', 12, '2025-07-08 16:48:14', 7, '6eme', 1, 11),
(25, 4, 'SPORT', 'Trimestre 1', 13, '2025-07-08 16:48:14', 7, '6eme', 1, 12),
(26, 1, 'ECM', 'Trimestre 1', 18, '2025-07-08 16:48:58', 6, '6eme', 2, 18),
(27, 4, 'ECM', 'Trimestre 1', 18, '2025-07-08 16:48:58', 6, '6eme', 2, 19),
(28, 1, 'MATHEMATIQUES', 'Trimestre 1', 16, '2025-07-08 16:50:21', 4, '6eme', 3, 14),
(29, 4, 'MATHEMATIQUES', 'Trimestre 1', 15, '2025-07-08 16:50:21', 4, '6eme', 3, 15);

-- --------------------------------------------------------

--
-- Structure de la table `professeurs`
--

DROP TABLE IF EXISTS `professeurs`;
CREATE TABLE IF NOT EXISTS `professeurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `professeurs`
--

INSERT INTO `professeurs` (`id`, `nom`, `email`, `telephone`, `mot_de_passe`, `created_at`) VALUES
(1, 'KOUMEBIO Kokou Christia', 'kokou@gmail.com', '99887766', '$2y$10$yDcLvkv.OUp3Ytg4TyMBVOhJYqH7az917RVCoXCZNTPMO3OnhnCoC', '2025-07-05 20:01:38'),
(2, 'MESSIGA Koffi', 'messiga@gmail.com', '72331100', '$2y$10$oUaQF6N/Hiia5vSHN0txceEE.yx2bZQ8qPyog8e7B2T5Dibm1Vrn.', '2025-07-06 12:35:04'),
(3, 'ATITSO', 'atitso@gmail.com', '71227899', '$2y$10$/DV0AC1goXJYlqwmXuxtwumlbI.paxx9oA3/IufrlqkKpnAF1AS1y', '2025-07-07 14:04:17'),
(4, 'ABIDOKO Blaise', 'blaise@gmail.com', '91821611', '$2y$10$aW0dQGbgM0xF7dN47OdHkuAcJUVJCk8GFKk//GmEM.cbHGlVhiFJu', '2025-07-08 09:57:18'),
(5, 'KOUDJODJI K. Kwami', 'koudjodji@gmail.com', '98332288', '$2y$10$JxbYARHdQRZtrvhTvwgPX.8VdwGDq1TbC1mZvHe2xddLrxa8CO15W', '2025-07-08 09:58:21'),
(6, 'KPONTON Q.E. Abel', 'kponton@gmail.com', '97119099', '$2y$10$ZuEjjLg.bTQ18ibVvfsCsecLR3k/lWxXq1F5ocMOWGL1GHaJVVC/a', '2025-07-08 09:59:11'),
(7, 'AMEDEE', 'amedee@gmail.com', '70119923', '$2y$10$DB0aVF9a6FZ0qC0kYdm/EuLQ/lKM4.ueZ6nl3Welg.JijbNU/C4HG', '2025-07-08 09:59:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
