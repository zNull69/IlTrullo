CREATE DATABASE IF NOT EXISTS azienda_agricola;
USE azienda_agricola;

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('fresco','riserva','confezionato') NOT NULL,
    unita VARCHAR(20),
    disponibile BOOLEAN DEFAULT TRUE,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorie(id)
);

CREATE TABLE prezzi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    prezzo DECIMAL(10,2) NOT NULL,
    data_inizio DATE NOT NULL,
    data_fine DATE NULL,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

CREATE TABLE clienti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nickname VARCHAR(50),
    contatto VARCHAR(100),
    occasionale BOOLEAN DEFAULT FALSE
);

CREATE TABLE luoghi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    tipo VARCHAR(50),
    attivo BOOLEAN DEFAULT TRUE
);

CREATE TABLE acquisti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_luogo INT DEFAULT 1,
    data_acquisto DATE NOT NULL,
    totale DECIMAL(10,2),
    totale_pagato DECIMAL(10,2),
    note TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clienti(id),
    FOREIGN KEY (id_luogo) REFERENCES luoghi(id)
);

CREATE TABLE righe_acquisto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_acquisto INT,
    id_prodotto INT,
    quantita DECIMAL(10,2),
    prezzo_unitario DECIMAL(10,2),
    omaggio BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_acquisto) REFERENCES acquisti(id),
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

INSERT INTO luoghi(id, nome, tipo) VALUES (1, 'Dispensa', 'magazzino');

CREATE TABLE lavorazioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT NOT NULL,
    id_luogo INT,
    tipo_lavorazione VARCHAR(100),
    data_lavorazione DATE NOT NULL,
    quantita_prodotta DECIMAL(10,2),
    unita_misura VARCHAR(20),
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id),
    FOREIGN KEY (id_luogo) REFERENCES luoghi(id)
);

CREATE TABLE riserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    id_lavorazione INT,
    id_luogo INT DEFAULT 1,
    quantita DECIMAL(10,2),
    data_produzione DATE,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id),
    FOREIGN KEY (id_lavorazione) REFERENCES lavorazioni(id),
    FOREIGN KEY (id_luogo) REFERENCES luoghi(id)
);

CREATE TABLE confezioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    id_riserva INT,
    id_luogo INT DEFAULT 1,
    quantita DECIMAL(10,2),
    peso_netto DECIMAL(10,3),
    prezzo DECIMAL(10,2),
    data_produzione_originale DATE,
    data_confezionamento DATE,
    giacenza DECIMAL(10,2),
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id),
    FOREIGN KEY (id_riserva) REFERENCES riserva(id),
    FOREIGN KEY (id_luogo) REFERENCES luoghi(id)
);

CREATE TABLE spostamenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    da_luogo INT,
    a_luogo INT,
    quantita DECIMAL(10,2),
    data_spostamento DATE,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id),
    FOREIGN KEY (da_luogo) REFERENCES luoghi(id),
    FOREIGN KEY (a_luogo) REFERENCES luoghi(id)
);

CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ruolo ENUM('admin','utente') DEFAULT 'utente'
);

ALTER TABLE acquisti
    ADD FOREIGN KEY (id_luogo) REFERENCES luoghi(id);

INSERT INTO utenti (username, password, ruolo)
VALUES ('admin', SHA2('admin', 256), 'admin');