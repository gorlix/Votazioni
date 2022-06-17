-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 18, 2022 alle 01:12
-- Versione del server: 5.7.17
-- Versione PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `votazioniscolastiche`
--
CREATE DATABASE IF NOT EXISTS `votazioniscolastiche` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `votazioniscolastiche`;

-- --------------------------------------------------------

--
-- Struttura della tabella `appartienea`
--

DROP TABLE IF EXISTS `appartienea`;
CREATE TABLE IF NOT EXISTS `appartienea` (
  `idUtente` int(11) NOT NULL,
  `idGruppo` int(11) NOT NULL,
  PRIMARY KEY (`idUtente`,`idGruppo`),
  KEY `idGruppo` (`idGruppo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `appartienea`
--

INSERT INTO `appartienea` (`idUtente`, `idGruppo`) VALUES
(1, 1),
(3, 1),
(1, 2),
(2, 2),
(3, 2),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `esegue`
--

DROP TABLE IF EXISTS `esegue`;
CREATE TABLE IF NOT EXISTS `esegue` (
  `idUtente` int(11) NOT NULL,
  `idVotazione` int(11) NOT NULL,
  `hash` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`idUtente`,`idVotazione`),
  UNIQUE KEY `hash` (`hash`),
  KEY `idVotazione` (`idVotazione`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `esegue`
--

INSERT INTO `esegue` (`idUtente`, `idVotazione`, `hash`) VALUES
(3, 1, NULL),
(3, 4, NULL),
(4, 1, NULL),
(4, 4, NULL),
(5, 1, NULL),
(5, 4, NULL),
(6, 1, NULL),
(6, 4, NULL),
(7, 1, NULL),
(7, 4, NULL),
(8, 1, NULL),
(8, 4, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `eseguegruppo`
--

DROP TABLE IF EXISTS `eseguegruppo`;
CREATE TABLE IF NOT EXISTS `eseguegruppo` (
  `idGruppo` int(11) NOT NULL,
  `idVotazione` int(11) NOT NULL,
  PRIMARY KEY (`idGruppo`,`idVotazione`),
  KEY `idVotazione` (`idVotazione`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo`
--

DROP TABLE IF EXISTS `gruppo`;
CREATE TABLE IF NOT EXISTS `gruppo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `gruppo`
--

INSERT INTO `gruppo` (`id`, `nome`) VALUES
(1, 'Admin'),
(2, 'Crea_votazione'),
(3, '5b inf');

-- --------------------------------------------------------

--
-- Struttura della tabella `opzione`
--

DROP TABLE IF EXISTS `opzione`;
CREATE TABLE IF NOT EXISTS `opzione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testo` varchar(40) NOT NULL,
  `nVoti` int(11) NOT NULL DEFAULT '0',
  `idVotazione` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idVotazione` (`idVotazione`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `opzione`
--

INSERT INTO `opzione` (`id`, `testo`, `nVoti`, `idVotazione`) VALUES
(1, 'si', 15, 1),
(2, 'no', 1, 1),
(7, 'Si', 4, 4),
(8, 'Ovvio', 2, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `recupero`
--

DROP TABLE IF EXISTS `recupero`;
CREATE TABLE IF NOT EXISTS `recupero` (
  `hash` varchar(256) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `dataScadenza` date NOT NULL,
  `oraScadenza` time NOT NULL,
  PRIMARY KEY (`hash`),
  KEY `idUtente` (`idUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `risposta`
--

DROP TABLE IF EXISTS `risposta`;
CREATE TABLE IF NOT EXISTS `risposta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idVotazione` int(11) NOT NULL,
  `idOpzione` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `risposta_ibfk_1` (`idUtente`),
  KEY `risposta_ibfk_2` (`idVotazione`),
  KEY `risposta_ibfk_3` (`idOpzione`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `risposta`
--

INSERT INTO `risposta` (`id`, `data`, `ora`, `idUtente`, `idVotazione`, `idOpzione`) VALUES
(1, '2022-06-15', '11:39:26', 3, 1, NULL),
(2, '2022-06-15', '11:42:55', 7, 1, NULL),
(3, '2022-06-15', '11:44:22', 6, 1, NULL),
(4, '2022-06-16', '12:00:31', 4, 1, NULL),
(5, '2022-06-16', '12:05:14', 5, 1, NULL),
(7, '2022-06-16', '12:26:52', 8, 1, NULL),
(8, '2022-06-17', '11:34:18', 8, 4, 7),
(9, '2022-06-17', '11:34:51', 7, 4, 7),
(10, '2022-06-17', '11:35:45', 3, 4, 8),
(11, '2022-06-18', '01:04:29', 5, 4, 8),
(12, '2022-06-18', '01:05:00', 4, 4, 7),
(13, '2022-06-18', '01:05:23', 6, 4, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

DROP TABLE IF EXISTS `utente`;
CREATE TABLE IF NOT EXISTS `utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pw` varchar(512) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `forzaModificaPW` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `pw`, `mail`, `nome`, `cognome`, `forzaModificaPW`) VALUES
(1, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'mail.prova@mail.com', 'Utente', 'Prova', 0),
(2, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'mail.prova2@mail.com', 'Utente2', 'Prova2', 0),
(3, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'andrea.tonello@alessandrinimainardi.edu.it', 'Andrea', 'Tonello', 0),
(4, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'gabriele.groppo@alessandrinimainardi.edu.it', 'Gabriele', 'Groppo', 0),
(5, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'alessandro.gorla@alessandrinimainardi.edu.it', 'Alessandro', 'Gorla', 0),
(6, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'francesco.moscaritoli@alessandrinimainardi.edu.it', 'Francesco', 'Moscaritoli', 0),
(7, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'matteo.schintu@alessandrinimainardi.edu.it', 'Matteo', 'Schintu', 0),
(8, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'negro@negro.it', 'N', 'N', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `votazione`
--

DROP TABLE IF EXISTS `votazione`;
CREATE TABLE IF NOT EXISTS `votazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quesito` varchar(40) NOT NULL,
  `tipo` enum('anonimo','nominale') DEFAULT NULL,
  `inizio` datetime NOT NULL,
  `fine` datetime NOT NULL,
  `quorum` float NOT NULL,
  `scelteMax` int(11) NOT NULL DEFAULT '1',
  `pubblica` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `votazione`
--

INSERT INTO `votazione` (`id`, `quesito`, `tipo`, `inizio`, `fine`, `quorum`, `scelteMax`, `pubblica`) VALUES
(1, 'Groppo con meno di 100', 'anonimo', '2022-06-14 22:34:00', '2022-06-16 00:00:01', 0, 1, 1),
(4, 'simone Ã¨ stupido2!', 'nominale', '2022-06-16 23:29:00', '2022-06-16 23:29:00', 0, 1, 1);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `appartienea`
--
ALTER TABLE `appartienea`
  ADD CONSTRAINT `appartienea_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `appartienea_ibfk_2` FOREIGN KEY (`idGruppo`) REFERENCES `gruppo` (`id`);

--
-- Limiti per la tabella `esegue`
--
ALTER TABLE `esegue`
  ADD CONSTRAINT `esegue_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `esegue_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `eseguegruppo`
--
ALTER TABLE `eseguegruppo`
  ADD CONSTRAINT `eseguegruppo_ibfk_1` FOREIGN KEY (`idGruppo`) REFERENCES `gruppo` (`id`),
  ADD CONSTRAINT `eseguegruppo_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`);

--
-- Limiti per la tabella `opzione`
--
ALTER TABLE `opzione`
  ADD CONSTRAINT `opzione_ibfk_1` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `recupero`
--
ALTER TABLE `recupero`
  ADD CONSTRAINT `recupero_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `risposta`
--
ALTER TABLE `risposta`
  ADD CONSTRAINT `risposta_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `risposta_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`),
  ADD CONSTRAINT `risposta_ibfk_3` FOREIGN KEY (`idOpzione`) REFERENCES `opzione` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
