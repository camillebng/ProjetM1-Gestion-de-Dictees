-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 28 Avril 2026 à 17:19
-- Version du serveur :  10.1.48-MariaDB-0ubuntu0.18.04.1
-- Version de PHP :  7.2.24-0ubuntu0.18.04.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gr4m1IDL`
--

-- --------------------------------------------------------

--
-- Structure de la table `toks_eleve`
--

CREATE TABLE `toks_eleve` (
  `id_toks_eleve` int(4) NOT NULL,
  `id_dict_fk` int(4) NOT NULL,
  `tok_eleve` varchar(30) NOT NULL,
  `position_eleve` int(100) NOT NULL,
  `est_correct` tinyint(1) NOT NULL,
  `pos_tok` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `toks_eleve`
--

INSERT INTO `toks_eleve` (`id_toks_eleve`, `id_dict_fk`, `tok_eleve`, `position_eleve`, `est_correct`, `pos_tok`) VALUES
(115, 1, 'En', 1, 1, ''),
(116, 1, 'été', 2, 1, ''),
(117, 1, 'les', 3, 1, ''),
(118, 1, 'salade', 4, 0, ''),
(119, 1, 'verte', 5, 0, ''),
(120, 1, 'pousse', 6, 0, ''),
(121, 1, 'dent', 7, 0, ''),
(122, 1, 'les', 8, 1, ''),
(123, 1, 'jardins', 9, 1, ''),
(124, 1, 'Les', 10, 1, ''),
(125, 1, 'jeune', 11, 0, ''),
(126, 1, 'caneton', 12, 0, ''),
(127, 1, 'picord', 13, 0, ''),
(128, 1, 'le', 14, 1, ''),
(129, 1, 'blé', 15, 1, ''),
(130, 1, 'avec', 16, 1, ''),
(131, 1, 'la', 17, 1, ''),
(132, 1, 'poule', 18, 1, ''),
(133, 1, 'noire', 19, 1, ''),
(134, 2, 'patin', 1, 1, ''),
(135, 2, 'capuchon', 2, 1, ''),
(136, 2, 'récréation', 3, 1, ''),
(137, 2, 'charitable', 4, 1, ''),
(138, 2, 'manifique', 5, 0, ''),
(139, 3, 'Le', 1, 1, ''),
(140, 3, 'corbeau', 2, 1, ''),
(141, 3, 'perché', 3, 1, ''),
(142, 3, 'sur', 4, 1, ''),
(143, 3, 'l\'', 5, 1, ''),
(144, 3, 'entenne', 6, 0, ''),
(145, 3, 'd\'', 7, 1, ''),
(146, 3, 'un', 8, 1, ''),
(147, 3, 'batiment', 9, 1, ''),
(148, 3, 'tient', 10, 1, ''),
(149, 3, 'dans', 11, 1, ''),
(150, 3, 'son', 12, 1, ''),
(151, 3, 'bec', 13, 1, ''),
(152, 3, 'une', 14, 1, ''),
(153, 3, 'souris', 15, 1, ''),
(154, 3, 'blessé', 16, 0, ''),
(155, 3, 'Rendu', 17, 1, ''),
(156, 3, 'furieux', 18, 1, ''),
(157, 3, 'par', 19, 1, ''),
(158, 3, 'cet', 20, 1, ''),
(159, 3, 'oiseau', 21, 1, ''),
(160, 3, 'cruel', 22, 1, ''),
(161, 3, 'des', 23, 1, ''),
(162, 3, 'enfants', 24, 1, ''),
(163, 3, 'lance', 25, 0, ''),
(164, 3, 'des', 26, 1, ''),
(165, 3, 'cailloux', 27, 1, ''),
(166, 3, 'pour', 28, 1, ''),
(167, 3, 'l\'', 29, 1, ''),
(168, 3, 'obligé', 30, 0, ''),
(169, 3, 'à', 31, 1, ''),
(170, 3, 's\'', 32, 1, ''),
(171, 3, 'envoler', 33, 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `toks_prof`
--

CREATE TABLE `toks_prof` (
  `id_toks` int(4) NOT NULL,
  `id_dict_fk` int(4) NOT NULL,
  `tok_prof` varchar(30) NOT NULL,
  `position_prof` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `toks_prof`
--

INSERT INTO `toks_prof` (`id_toks`, `id_dict_fk`, `tok_prof`, `position_prof`) VALUES
(115, 1, 'En', 1),
(116, 1, 'été', 2),
(117, 1, 'les', 3),
(118, 1, 'salades', 4),
(119, 1, 'vertes', 5),
(120, 1, 'poussent', 6),
(121, 1, 'dans', 7),
(122, 1, 'les', 8),
(123, 1, 'jardins', 9),
(124, 1, 'Les', 10),
(125, 1, 'jeunes', 11),
(126, 1, 'canetons', 12),
(127, 1, 'picorent', 13),
(128, 1, 'le', 14),
(129, 1, 'blé', 15),
(130, 1, 'avec', 16),
(131, 1, 'la', 17),
(132, 1, 'poule', 18),
(133, 1, 'noire', 19),
(134, 2, 'patin', 1),
(135, 2, 'capuchon', 2),
(136, 2, 'récréation', 3),
(137, 2, 'charitable', 4),
(138, 2, 'magnifique', 5),
(139, 3, 'Le', 1),
(140, 3, 'corbeau', 2),
(141, 3, 'perché', 3),
(142, 3, 'sur', 4),
(143, 3, 'l\'', 5),
(144, 3, 'antenne', 6),
(145, 3, 'd\'', 7),
(146, 3, 'un', 8),
(147, 3, 'bâtiment', 9),
(148, 3, 'tient', 10),
(149, 3, 'dans', 11),
(150, 3, 'son', 12),
(151, 3, 'bec', 13),
(152, 3, 'une', 14),
(153, 3, 'souris', 15),
(154, 3, 'blessée', 16),
(155, 3, 'Rendu', 17),
(156, 3, 'furieux', 18),
(157, 3, 'par', 19),
(158, 3, 'cet', 20),
(159, 3, 'oiseau', 21),
(160, 3, 'cruel', 22),
(161, 3, 'des', 23),
(162, 3, 'enfants', 24),
(163, 3, 'lancent', 25),
(164, 3, 'des', 26),
(165, 3, 'cailloux', 27),
(166, 3, 'pour', 28),
(167, 3, 'l\'', 29),
(168, 3, 'obliger', 30),
(169, 3, 'à', 31),
(170, 3, 's\'', 32),
(171, 3, 'envoler', 33);

-- --------------------------------------------------------

--
-- Structure de la table `version_eleve`
--

CREATE TABLE `version_eleve` (
  `id_dict_eleve` int(8) NOT NULL,
  `date` date NOT NULL,
  `contenu_eleve` text NOT NULL,
  `dict_fk` int(8) NOT NULL,
  `is_tokenized_e` tinyint(4) NOT NULL DEFAULT '0',
  `score_sur_20` decimal(3,1) NOT NULL DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `version_eleve`
--

INSERT INTO `version_eleve` (`id_dict_eleve`, `date`, `contenu_eleve`, `dict_fk`, `is_tokenized_e`, `score_sur_20`) VALUES
(1, '2026-03-09', 'En été, les salade verte pousse dent les jardins. Les jeune caneton picord le blé avec la poule noire.', 1, 1, '12.6'),
(2, '2026-03-11', 'patin capuchon récréation charitable manifique', 2, 1, '16.0'),
(3, '2026-03-16', 'Le corbeau, perché sur l\'entenne d\'un batiment, tient dans son bec une souris blessé. Rendu furieux par cet oiseau cruel des enfants lance des cailloux pour l\'obligé à s\'envoler. ', 3, 1, '17.6');

-- --------------------------------------------------------

--
-- Structure de la table `version_prof`
--

CREATE TABLE `version_prof` (
  `id_dict` int(8) NOT NULL,
  `type` varchar(6) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `niveau` varchar(3) NOT NULL,
  `contenu_prof` text NOT NULL,
  `is_tokenized` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `version_prof`
--

INSERT INTO `version_prof` (`id_dict`, `type`, `titre`, `niveau`, `contenu_prof`, `is_tokenized`) VALUES
(1, 'phrase', 'test_phrase', 'ce1', 'En été, les salades vertes poussent dans les jardins. Les jeunes canetons picorent le blé avec la poule noire.', 1),
(2, 'mot', 'test_mot', 'cp', 'patin capuchon récréation charitable magnifique', 1),
(3, 'texte', 'test_le_corbeau', 'cm1', 'Le corbeau, perché sur l\'antenne d\'un bâtiment, tient dans son bec une souris blessée. Rendu furieux par cet oiseau cruel, des enfants lancent des cailloux pour l\'obliger à s\'envoler.', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `toks_eleve`
--
ALTER TABLE `toks_eleve`
  ADD PRIMARY KEY (`id_toks_eleve`),
  ADD KEY `id_dict_fk` (`id_dict_fk`);

--
-- Index pour la table `toks_prof`
--
ALTER TABLE `toks_prof`
  ADD PRIMARY KEY (`id_toks`),
  ADD KEY `id_dict_fk` (`id_dict_fk`);

--
-- Index pour la table `version_eleve`
--
ALTER TABLE `version_eleve`
  ADD PRIMARY KEY (`id_dict_eleve`),
  ADD KEY `dict_fk` (`dict_fk`);

--
-- Index pour la table `version_prof`
--
ALTER TABLE `version_prof`
  ADD PRIMARY KEY (`id_dict`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `toks_eleve`
--
ALTER TABLE `toks_eleve`
  MODIFY `id_toks_eleve` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;
--
-- AUTO_INCREMENT pour la table `toks_prof`
--
ALTER TABLE `toks_prof`
  MODIFY `id_toks` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;
--
-- AUTO_INCREMENT pour la table `version_eleve`
--
ALTER TABLE `version_eleve`
  MODIFY `id_dict_eleve` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `version_prof`
--
ALTER TABLE `version_prof`
  MODIFY `id_dict` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `toks_eleve`
--
ALTER TABLE `toks_eleve`
  ADD CONSTRAINT `toks_eleve_ibfk_1` FOREIGN KEY (`id_dict_fk`) REFERENCES `version_prof` (`id_dict`);

--
-- Contraintes pour la table `toks_prof`
--
ALTER TABLE `toks_prof`
  ADD CONSTRAINT `toks_prof_ibfk_1` FOREIGN KEY (`id_dict_fk`) REFERENCES `version_prof` (`id_dict`);

--
-- Contraintes pour la table `version_eleve`
--
ALTER TABLE `version_eleve`
  ADD CONSTRAINT `version_eleve_ibfk_1` FOREIGN KEY (`dict_fk`) REFERENCES `version_prof` (`id_dict`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
