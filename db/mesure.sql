-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 12 juin 2024 à 09:40
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
-- Base de données : `mesure`
--

-- --------------------------------------------------------

--
-- Structure de la table `mesure`
--

DROP TABLE IF EXISTS `mesure`;
CREATE TABLE IF NOT EXISTS `mesure` (
  `ID_mesure` int NOT NULL,
  `DateHeure` date NOT NULL,
  `Humidite` decimal(10,0) NOT NULL,
  `Temperature` decimal(10,0) NOT NULL,
  `Volt` decimal(10,0) NOT NULL,
  `Ampere` decimal(10,0) NOT NULL,
  PRIMARY KEY (`ID_mesure`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `mesure`
--

INSERT INTO `mesure` (`ID_mesure`, `DateHeure`, `Humidite`, `Temperature`, `Volt`, `Ampere`) VALUES
(1, '2024-06-11', 38, 25, 8, 44),
(2, '2024-06-11', 35, 24, 15, 50),
(3, '2024-06-12', 32, 28, 32, 48),
(4, '2024-06-11', 24, 24, 27, 42);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
