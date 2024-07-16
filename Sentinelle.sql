-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 08 mai 2024 à 02:25
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Sentinelle`
--

-- --------------------------------------------------------

--
-- Structure de la table `Donnees_zumo`
--

CREATE TABLE `Donnees_zumo` (
  `Point_chaud` float NOT NULL,
  `Fumee` text NOT NULL,
  `Temperature` float NOT NULL,
  `Direction` text NOT NULL,
  `Emplacement` text NOT NULL,
  `Horaire` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IDrobot` int(11) NOT NULL,
  `Point_chaudd` float NOT NULL,
  `Emplacementt` text NOT NULL,
  `Obstacle` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
