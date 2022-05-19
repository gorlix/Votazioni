-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: votazioniscolastiche
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
create database if not exists `votazioniscolastiche`;
use `votazioniscolastiche`;
--
-- Table structure for table `appartienea`
--

DROP TABLE IF EXISTS `appartienea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appartienea` (
  `idUtente` int NOT NULL,
  `idGruppo` int NOT NULL,
  PRIMARY KEY (`idUtente`,`idGruppo`),
  KEY `idGruppo` (`idGruppo`),
  CONSTRAINT `appartienea_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  CONSTRAINT `appartienea_ibfk_2` FOREIGN KEY (`idGruppo`) REFERENCES `gruppo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appartienea`
--

LOCK TABLES `appartienea` WRITE;
/*!40000 ALTER TABLE `appartienea` DISABLE KEYS */;
/*!40000 ALTER TABLE `appartienea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `esegue`
--

DROP TABLE IF EXISTS `esegue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `esegue` (
  `idUtente` int NOT NULL,
  `idVotazione` int NOT NULL,
  `hash` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`idUtente`,`idVotazione`),
  UNIQUE KEY `hash` (`hash`),
  KEY `idVotazione` (`idVotazione`),
  CONSTRAINT `esegue_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  CONSTRAINT `esegue_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `esegue`
--

LOCK TABLES `esegue` WRITE;
/*!40000 ALTER TABLE `esegue` DISABLE KEYS */;
/*!40000 ALTER TABLE `esegue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gruppo`
--

DROP TABLE IF EXISTS `gruppo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gruppo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gruppo`
--

LOCK TABLES `gruppo` WRITE;
/*!40000 ALTER TABLE `gruppo` DISABLE KEYS */;
/*!40000 ALTER TABLE `gruppo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opzione`
--

DROP TABLE IF EXISTS `opzione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opzione` (
  `id` int NOT NULL AUTO_INCREMENT,
  `testo` varchar(40) NOT NULL,
  `nVoti` int NOT NULL DEFAULT '0',
  `idVotazione` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idVotazione` (`idVotazione`),
  CONSTRAINT `opzione_ibfk_1` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`),
  CONSTRAINT `nVoti_ChecknVoti` CHECK ((`nVoti` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opzione`
--

LOCK TABLES `opzione` WRITE;
/*!40000 ALTER TABLE `opzione` DISABLE KEYS */;
/*!40000 ALTER TABLE `opzione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recupero`
--

DROP TABLE IF EXISTS `recupero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recupero` (
  `hash` varchar(256) NOT NULL,
  `idUtente` int NOT NULL,
  `dataScadenza` date NOT NULL,
  `oraScadenza` time NOT NULL,
  PRIMARY KEY (`hash`),
  KEY `idUtente` (`idUtente`),
  CONSTRAINT `recupero_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recupero`
--

LOCK TABLES `recupero` WRITE;
/*!40000 ALTER TABLE `recupero` DISABLE KEYS */;
/*!40000 ALTER TABLE `recupero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risposta`
--

DROP TABLE IF EXISTS `risposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `risposta` (
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `idUtente` int NOT NULL,
  `idVotazione` int NOT NULL,
  `idOpzione` int NOT NULL,
  PRIMARY KEY (`idUtente`,`idVotazione`,`idOpzione`),
  KEY `idVotazione` (`idVotazione`),
  KEY `idOpzione` (`idOpzione`),
  CONSTRAINT `risposta_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`),
  CONSTRAINT `risposta_ibfk_2` FOREIGN KEY (`idVotazione`) REFERENCES `votazione` (`id`),
  CONSTRAINT `risposta_ibfk_3` FOREIGN KEY (`idOpzione`) REFERENCES `opzione` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risposta`
--

LOCK TABLES `risposta` WRITE;
/*!40000 ALTER TABLE `risposta` DISABLE KEYS */;
/*!40000 ALTER TABLE `risposta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pw` varchar(512) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `forzaModificaPW` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES (1,'1e4e888ac66f8dd41e00c5a7ac36a32a9950d271','mail.prova@mail.com','Utente','Prova',0);
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votazione`
--

DROP TABLE IF EXISTS `votazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votazione` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quesito` varchar(40) NOT NULL,
  `tipo` enum('anonimo','nominale') DEFAULT NULL,
  `inizio` datetime NOT NULL,
  `fine` datetime NOT NULL,
  `quorum` float NOT NULL,
  `scelteMax` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  CONSTRAINT `votazioni_CheckQuorum` CHECK ((`quorum` between 0 and 100)),
  CONSTRAINT `votazioni_CheckScelteMax` CHECK ((`scelteMax` >= 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votazione`
--

LOCK TABLES `votazione` WRITE;
/*!40000 ALTER TABLE `votazione` DISABLE KEYS */;
/*!40000 ALTER TABLE `votazione` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-19 11:32:43
