-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 16 Avril 2016 à 18:38
-- Version du serveur :  10.1.9-MariaDB
-- Version de PHP :  5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `soundify`
--

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Casque');

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_firstname`, `user_address`, `user_zipcode`, `user_mail`, `user_password`, `user_salt`, `user_role`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', 'admin@admin.com', 'S2S1p0+LapgCHaxWx95G+fjrA2WNw3s5s8lth0a2KIcl/I7wgVwfnOl2x3/pCwLgjU1N02lm+iISuW91aLYV7w==', '98a546878f30f2c099a666c', 'ROLE_ADMIN');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
