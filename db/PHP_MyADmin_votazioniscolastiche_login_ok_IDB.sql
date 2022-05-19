-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 19, 2022 alle 11:57
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

CREATE TABLE `appartienea` (
  `idUtente` int(11) NOT NULL,
  `idGruppo` int(11) NOT NULL
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

CREATE TABLE `esegue` (
  `idUtente` int(11) NOT NULL,
  `idVotazione` int(11) NOT NULL,
  `hash` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo`
--

CREATE TABLE `gruppo` (
  `id` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `opzione` (
  `id` int(11) NOT NULL,
  `testo` varchar(40) NOT NULL,
  `nVoti` int(11) NOT NULL DEFAULT '0',
  `idVotazione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `recupero`
--

CREATE TABLE `recupero` (
  `hash` varchar(256) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `dataScadenza` date NOT NULL,
  `oraScadenza` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `risposta`
--

CREATE TABLE `risposta` (
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idVotazione` int(11) NOT NULL,
  `idOpzione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `pw` varchar(512) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `forzaModificaPW` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `votazione` (
  `id` int(11) NOT NULL,
  `quesito` varchar(40) NOT NULL,
  `tipo` enum('anonimo','nominale') DEFAULT NULL,
  `inizio` datetime NOT NULL,
  `fine` datetime NOT NULL,
  `quorum` float NOT NULL,
  `scelteMax` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `appartienea`
--
ALTER TABLE `appartienea`
  ADD PRIMARY KEY (`idUtente`,`idGruppo`),
  ADD KEY `idGruppo` (`idGruppo`);

--
-- Indici per le tabelle `esegue`
--
ALTER TABLE `esegue`
  ADD PRIMARY KEY (`idUtente`,`idVotazione`),
  ADD UNIQUE KEY `hash` (`hash`),
  ADD KEY `idVotazione` (`idVotazione`);

--
-- Indici per le tabelle `gruppo`
--
ALTER TABLE `gruppo`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `opzione`
--
ALTER TABLE `opzione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVotazione` (`idVotazione`);

--
-- Indici per le tabelle `recupero`
--
ALTER TABLE `recupero`
  ADD PRIMARY KEY (`hash`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `risposta`
--
ALTER TABLE `risposta`
  ADD PRIMARY KEY (`idUtente`,`idVotazione`,`idOpzione`),
  ADD KEY `idVotazione` (`idVotazione`),
  ADD KEY `idOpzione` (`idOpzione`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `votazione`
--
ALTER TABLE `votazione`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `gruppo`
--
ALTER TABLE `gruppo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `opzione`
--
ALTER TABLE `opzione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `votazione`
--
ALTER TABLE `votazione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
