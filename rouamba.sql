-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 30 juil. 2024 à 13:31
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `numeroRD` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  `cnib` varchar(20) COLLATE utf32_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`numeroRD`)
) ENGINE=MyISAM AUTO_INCREMENT=308 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;



DROP TABLE IF EXISTS `consonsommation`;
CREATE TABLE IF NOT EXISTS `consonsommation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numeroRD` int NOT NULL,
  `mois` int NOT NULL,
  `annee` int NOT NULL,
  `consom3` decimal(10,2) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `numeroRD` (`numeroRD`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;


DROP TABLE IF EXISTS `etatpaiement`;
CREATE TABLE IF NOT EXISTS `etatpaiement` (
  `numeroRD` int NOT NULL,
  `mois` int NOT NULL,
  `annee` int NOT NULL,
  `etat` varchar(10) COLLATE utf32_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`numeroRD`,`mois`,`annee`)
) ;


DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `numeroRD` int NOT NULL,
  `mois` int NOT NULL,
  `annee` int NOT NULL,
  `consom3` double DEFAULT NULL,
  `montant` double DEFAULT NULL,
  `typedepaiement` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`numeroRD`,`mois`,`annee`)
) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf32_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf32_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
