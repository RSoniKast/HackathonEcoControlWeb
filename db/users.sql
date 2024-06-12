-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 12 juin 2024 à 09:42
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
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation_compte` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Date_naissance` varchar(250) NOT NULL,
  `photo_profil` varchar(255) DEFAULT NULL,
  `description` text,
  `administrateur` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `mot_de_passe`, `date_creation_compte`, `Date_naissance`, `photo_profil`, `description`, `administrateur`) VALUES
(1, '122', 'lz.zaercher@gmail.com', '$2y$10$3A8JThJvUClIaz5aX7hzMeGLdYTsohxGqdPd.S8YaLdLTDES37afe', '2023-06-05 09:22:43', '', 'profile_pics/Eren.Jaeger.full.3748985.jpg', 'gtfo', 1),
(4, 'Testeur', 'loiczaercher2@gmail.com', '$2y$10$PmShOiNH1gXNEWT.P8GsuOK4.DjEpfPNnlkF3qIWLVhbmDS1sSS26', '2024-06-05 09:22:43', '', NULL, NULL, 0),
(5, 'test', 'test@gmail.com', '$2y$10$CE43XHRhlGlpbVK3zt/2F.RE4IiQWt57U7rdqu2Z5jDcsqShBiAdC', '2023-06-01 09:46:02', '26/04/2005', 'profile_pics/Updated_PP2.jpg', 'Yo', 0),
(6, 'username', 'ballamoussa57@gmail.com', '$2y$10$aTqaDwLjYnf0KfmunHWVEOSBBMVNs0zs1ttubdohbx97pWWbdLcGm', '2024-06-11 16:04:57', '2004-11-18', NULL, NULL, 1),
(7, 'balla', 'sonikast@hotmail.com', '$2y$10$1uiXwJmNg1PZtCouyZyVOer7DSvmoJadJzV6hEqtclNUj0f/mRtz6', '2024-06-12 10:07:30', '2004-11-18', NULL, NULL, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
