-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 11 mai 2024 à 12:57
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `user_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `horaire_profs`
--

DROP TABLE IF EXISTS `horaire_profs`;
CREATE TABLE IF NOT EXISTS `horaire_profs` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `id_prof` int NOT NULL,
  `jour` varchar(20) NOT NULL,
  `heure` int NOT NULL,
  `classe` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `id_prof` (`id_prof`,`jour`,`heure`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `horaire_profs`
--

INSERT INTO `horaire_profs` (`ID`, `id_prof`, `jour`, `heure`, `classe`) VALUES
(15, 15, 'lundi', 5, '6TTI'),
(16, 15, 'lundi', 8, '6TTI'),
(17, 15, 'mardi', 2, '6TTI'),
(18, 15, 'lundi', 4, '6TTSEA');

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `id_eleve` int NOT NULL,
  `jour` varchar(20) NOT NULL,
  `etat` varchar(20) NOT NULL,
  `heure` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `presences`
--

INSERT INTO `presences` (`ID`, `id_eleve`, `jour`, `etat`, `heure`) VALUES
(43, 1, 'lundi', 'retard', 7),
(44, 1, 'lundi', 'retard', 6),
(45, 2, 'lundi', 'present', 2),
(46, 2, 'lundi', 'present', 4),
(47, 1, 'lundi', 'present', 9),
(48, 2, 'lundi', 'absent', 6),
(49, 1, 'lundi', 'absent', 1),
(50, 1, 'lundi', 'absent', 2),
(51, 2, 'lundi', 'present', 1),
(52, 3, 'lundi', 'absent', 4),
(53, 2, 'vendredi', 'retard', 3),
(54, 2, 'jeudi', 'retard', 6),
(55, 2, 'mardi', 'absent', 2),
(56, 1, 'mardi', 'present', 2),
(57, 4, 'mardi', 'retard', 3),
(58, 1, 'lundi', 'retard', 5),
(59, 2, 'lundi', 'absent', 5),
(60, 1, 'vendredi', 'present', 8);

-- --------------------------------------------------------

--
-- Structure de la table `rapport`
--

DROP TABLE IF EXISTS `rapport`;
CREATE TABLE IF NOT EXISTS `rapport` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `eleve` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `classe` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `rapport` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`ID`, `eleve`, `classe`, `rapport`, `type`) VALUES
(27, 'Bastien Dupont', '6TTI', 'frappe sur un éleve', ''),
(28, 'Dary Caram', '4TTSE', 'crache sur la prof', ''),
(33, 'Dary Caram', '4TTSE', 'eurghrofiyu', ''),
(34, 'Dary Caram', '4TTSE', 'comment il va mon petit ?', ''),
(36, 'Dary Caram', '6TTSEA', 'zaazdazd', 'disciplinaire'),
(37, 'Bastien Dupont', '6TTI', 'azdzadazd', 'disciplinaire'),
(39, 'Noa Claes', '6TTI', 'efzef', 'disciplinaire'),
(40, 'Noa Claes', '6TTI', 'deze', 'disciplinaire'),
(41, 'Lou Vanbe', '6TTI', 'ezfzeef', 'tenue_vestimentaire');

-- --------------------------------------------------------

--
-- Structure de la table `sandwiches`
--

DROP TABLE IF EXISTS `sandwiches`;
CREATE TABLE IF NOT EXISTS `sandwiches` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `eleve` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `classe` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantite` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `modif` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `approuve` int DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sandwiches`
--

INSERT INTO `sandwiches` (`ID`, `eleve`, `classe`, `quantite`, `type`, `modif`, `approuve`) VALUES
(6, 'Bastien Dupont', '6TTI', 3, 'usa', 'sans tomate ni oignons mais je veux bien gouda', 1);

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `classe` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `student`
--

INSERT INTO `student` (`ID`, `prenom`, `nom`, `classe`) VALUES
(1, 'Bastien', 'Dupont', '6TTI'),
(2, 'Noa ', 'Claes', '6TTI'),
(3, 'Lou', 'Vanbe', '6TTSEA'),
(4, 'Dary', 'Caram', '6TTSEA');

-- --------------------------------------------------------

--
-- Structure de la table `user_form`
--

DROP TABLE IF EXISTS `user_form`;
CREATE TABLE IF NOT EXISTS `user_form` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `certification` int DEFAULT NULL,
  `classe` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `matière` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `surname`, `email`, `password`, `user_type`, `certification`, `classe`, `matière`) VALUES
(11, 'mami', 'oups', 'mami@gmail.com', '$2y$10$UONVOPQ11jd92/innCx9U.9kl7/KllQ/mBsu1.bHGXV7ETVE8inba', 'admin', 1, '5TTE', 'sciences'),
(15, 'Roussel', 'Jean-Marc', 'Roussel@gmail.com', '$2y$10$KoxUsqxOTDrHz1pGfx6UGuzThZCjpRMcQsU7SlK2zoPjxs9hw7LIK', 'user', 1, '6TTI', 'Informatique et labo logique/informatique'),
(17, 'Roussel', 'Jean-Marc', 'Roussel_admin@gmail.com', '$2y$10$0J.PCRMkhLtgX3/h4Up7DO1lSu1dT1zwP1PoTlpfXUV1l4V6uvT2.', 'admin', 1, '', ''),
(18, 'toto', 'toto', 'toto@gmail.com', '$2y$10$ftxDCLlCrS5C0n2j7obdRe777YQmjCLa3CfOs8MQN8Rm9336BTQwC', 'user', 1, '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
