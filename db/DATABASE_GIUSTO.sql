-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 01, 2022 alle 17:53
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
(1, 2),
(2, 2);

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
(1, 1, 'A0C299B71A9E59D5EBB07917E70601A3570AA103E99A7BB65A58E780EC9077B1902D1DEDB31B1457BEDA595FE4D71D779B6CA9CAD476266CC07590E31D84B206'),
(2, 2, 'C34D427B8B54B254AE843269019A6D5B747783DD230B0A18D66E6CFAE072CEC3339D8B571FFFCABCD6182D083EF3938A0260205A63E9F568582BFC601376BA83');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo`
--

DROP TABLE IF EXISTS `gruppo`;
CREATE TABLE IF NOT EXISTS `gruppo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `gruppo`
--

INSERT INTO `gruppo` (`id`, `nome`) VALUES
(1, 'Admin'),
(2, 'Crea_votazione');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `opzione`
--

INSERT INTO `opzione` (`id`, `testo`, `nVoti`, `idVotazione`) VALUES
(1, 'Si', 30, 1),
(2, 'No', 9, 1),
(3, 'Roma', 14, 2),
(4, 'Milano', 7, 2),
(5, 'Torino', 8, 2),
(6, 'Genova', 6, 2);

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
  KEY `idUtente` (`idUtente`),
  KEY `idVotazione` (`idVotazione`),
  KEY `idOpzione` (`idOpzione`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `risposta`
--

INSERT INTO `risposta` (`id`, `data`, `ora`, `idUtente`, `idVotazione`, `idOpzione`) VALUES
(22, '2022-06-01', '05:39:20', 1, 1, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `pw`, `mail`, `nome`, `cognome`, `forzaModificaPW`) VALUES
(1, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'mail.prova@mail.com', 'Utente', 'Prova', 0),
(2, '1e4e888ac66f8dd41e00c5a7ac36a32a9950d271', 'mail.prova2@mail.com', 'Utente2', 'Prova2', 0);

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
(1, 'Scuola al Sabato?', 'anonimo', '2022-01-01 00:00:00', '2022-06-02 00:00:00', 75, 1, 0),
(2, 'Dove preferisci andare in gita?', 'nominale', '2022-01-01 00:00:00', '2022-05-02 00:00:00', 75, 2, 1),
(3, 'Creiamo il reparto Gianni?', 'anonimo', '2022-01-01 00:00:00', '2022-03-08 00:00:00', 75, 1, 0),
(4, 'è meglio C o Pyton?', 'anonimo', '2022-01-01 00:00:00', '2023-02-02 00:00:00', 75, 1, 0);

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
  ADD CONSTRAINT `esegue_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `esegue_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`);

--
-- Limiti per la tabella `opzione`
--
ALTER TABLE `opzione`
  ADD CONSTRAINT `opzione_ibfk_1` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`);

--
-- Limiti per la tabella `recupero`
--
ALTER TABLE `recupero`
  ADD CONSTRAINT `recupero_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
