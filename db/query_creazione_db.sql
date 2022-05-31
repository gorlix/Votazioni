drop database votazioniScolastiche;

create database votazioniScolastiche;

use votazioniScolastiche;

create table utente(
  id int NOT NULL AUTO_INCREMENT,
  pw varchar(30) NOT NULL,
  mail varchar(50) NOT NULL,
  nome varchar(30) NOT NULL,
  cognome varchar(30) NOT NULL,
  forzaModificaPW boolean NOT NULL,
  PRIMARY KEY(id)
);

create table gruppo(
  id int NOT NULL AUTO_INCREMENT,
  nome varchar(30) NOT NULL,
  PRIMARY KEY(id)
);

create table appartieneA(
  idUtente int NOT NULL,
  idGruppo int NOT NULL,
  FOREIGN KEY(idUtente) REFERENCES utente(id),
  FOREIGN KEY(idGruppo) REFERENCES gruppo(id),
  PRIMARY KEY(idUtente, idGruppo)
);

create table votazione(
  id int NOT NULL AUTO_INCREMENT,
  quesito varchar(40) NOT NULL,
  tipo ENUM('anonimo','nominale'),
  pubblica boolean DEFAULT false,
  inizio dateTime NOT NULL,
  fine dateTime NOT NULL,
  quorum float NOT NULL,
  scelteMax int NOT NULL DEFAULT 1,
  CONSTRAINT votazioni_CheckQuorum CHECK(quorum BETWEEN 0 AND 100),
  CONSTRAINT votazioni_CheckScelteMax CHECK(scelteMax >= 1),
  PRIMARY KEY(id)
);

create table opzione(
  id int NOT NULL AUTO_INCREMENT,
  testo varchar(40) NOT NULL,
  nVoti int NOT NULL DEFAULT 0,
  CONSTRAINT nVoti_ChecknVoti CHECK(nVoti >= 0),
  idVotazione int NOT NULL,
  FOREIGN KEY(idVotazione) REFERENCES votazione(id),
  PRIMARY KEY(id)
);

create table esegue(
  idUtente int NOT NULL,
  idVotazione int NOT NULL,
  hash varchar(512),
  FOREIGN KEY(idUtente) REFERENCES utente(id),
  FOREIGN KEY(idVotazione) REFERENCES votazione(id),
  PRIMARY KEY(idUtente,idVotazione)
);

create table risposta(
  id int NOT NULL AUTO_INCREMENT,
  data date NOT NULL,
  ora time NOT NULL,
  idUtente int NOT NULL,
  idVotazione int NOT NULL,
  idOpzione int,
  FOREIGN KEY(idUtente) REFERENCES utente(id),
  FOREIGN KEY(idVotazione) REFERENCES votazione(id),
  FOREIGN KEY(idOpzione) REFERENCES opzione(id),
  PRIMARY KEY(id)
);

create table recupero(
  hash varchar(512),
  idUtente int NOT NULL,
  dataScadenza date NOT NULL,
  oraScadenza time NOT NULL,
  FOREIGN KEY(idUtente) REFERENCES utente(id),
  PRIMARY KEY(hash)
);
