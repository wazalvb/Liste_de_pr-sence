-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 12 mai 2024 à 19:24
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

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
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `id_prof` int(11) NOT NULL,
  `jour` varchar(20) NOT NULL,
  `heure` int(11) NOT NULL,
  `classe` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `id_prof` (`id_prof`,`jour`,`heure`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `horaire_profs`
--

INSERT INTO `horaire_profs` (`ID`, `id_prof`, `jour`, `heure`, `classe`) VALUES
(15, 15, 'lundi', 5, '6TTI'),
(16, 15, 'lundi', 8, '6TTI'),
(17, 15, 'mardi', 2, '6TTI'),
(18, 18, 'lundi', 4, '6TTSEA');

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `jour` varchar(20) NOT NULL,
  `etat` varchar(20) NOT NULL,
  `heure` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

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
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `eleve` varchar(100) NOT NULL,
  `classe` varchar(20) NOT NULL,
  `rapport` varchar(500) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`ID`, `eleve`, `classe`, `rapport`, `type`) VALUES
(40, 'Noa Claes', '6TTI', 'Insulte un élève dans les couloirs', 'disciplinaire'),
(41, 'Lou Vanbe', '6TTSEA', 'Habillé en jogging dans l\'école', 'tenue_vestimentaire');

-- --------------------------------------------------------

--
-- Structure de la table `sandwiches`
--

DROP TABLE IF EXISTS `sandwiches`;
CREATE TABLE IF NOT EXISTS `sandwiches` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `eleve` varchar(200) NOT NULL,
  `classe` varchar(100) NOT NULL,
  `quantite` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `modif` varchar(150) NOT NULL,
  `approuve` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sandwiches`
--

INSERT INTO `sandwiches` (`ID`, `eleve`, `classe`, `quantite`, `type`, `modif`, `approuve`) VALUES
(6, 'Bastien Dupont', '6TTI', 3, 'usa', 'sans tomate ni oignons mais je veux bien gouda', 1),
(7, 'Lou Vanbe', '6TTSEA', 1, 'usa', 'sans salade', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `classe` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL,
  `user_type` varchar(100) NOT NULL DEFAULT 'user',
  `certification` int(11) DEFAULT NULL,
  `classe` varchar(20) NOT NULL,
  `matière` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `surname`, `email`, `password`, `user_type`, `certification`, `classe`, `matière`) VALUES
(15, 'Roussel', 'Jean-Marc', 'Roussel@gmail.com', '$2y$10$KoxUsqxOTDrHz1pGfx6UGuzThZCjpRMcQsU7SlK2zoPjxs9hw7LIK', 'user', 1, '6TTI', 'Informatique et labo logique/informatique'),
(17, 'Roussel', 'Jean-Marc', 'Roussel_admin@gmail.com', '$2y$10$0J.PCRMkhLtgX3/h4Up7DO1lSu1dT1zwP1PoTlpfXUV1l4V6uvT2.', 'admin', 1, '', ''),
(18, 'toto', 'titi', 'toto@gmail.com', '$2y$10$ftxDCLlCrS5C0n2j7obdRe777YQmjCLa3CfOs8MQN8Rm9336BTQwC', 'user', 1, '', ''),
(19, 'Merlin', 'Marc', 'Merlin@gmail.com', 'Merlin', 'user', 1, '5TTI', 'TPT'),
(20, 'Pirlot', 'Romy', 'Pirlot@gmail.com', 'pirlot', 'admin', 1, '', ''),
(21, 'Van Douret', 'Julie', 'VanDouret@gmail.com', 'julie', 'user', 1, '6TTSEA', 'Mathématique'),
(22, '', '', '', '(j\'ai inserré manuelement dans la db les mots de passe de certain utilisateurs, comme ça vous savez vou connectez avec ceux là) c\'est pour ça qu\'il y en a certain non hashé', 'user', NULL, '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
