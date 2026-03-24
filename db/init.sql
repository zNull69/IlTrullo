CREATE DATABASE IF NOT EXISTS azienda_agricola;
USE azienda_agricola;

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) UNIQUE
);

CREATE TABLE prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    tipo ENUM('fresco','riserva','confezionato'),
    unita VARCHAR(20),
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorie(id)
);

CREATE TABLE prezzi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    prezzo DECIMAL(10,2),
    data_inizio DATE,
    data_fine DATE NULL,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

CREATE TABLE clienti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    nickname VARCHAR(50),
    contatto VARCHAR(100)
);

CREATE TABLE acquisti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    data_acquisto DATE,
    totale DECIMAL(10,2),
    totale_pagato DECIMAL(10,2),
    note TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clienti(id)
);

CREATE TABLE righe_acquisto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_acquisto INT,
    id_prodotto INT,
    quantita DECIMAL(10,2),
    prezzo_unitario DECIMAL(10,2),
    FOREIGN KEY (id_acquisto) REFERENCES acquisti(id),
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

CREATE TABLE riserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    quantita DECIMAL(10,2),
    data_produzione DATE,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

CREATE TABLE confezioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prodotto INT,
    quantita INT,
    data_confezionamento DATE,
    giacenza INT,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id)
);

-- UTENTE ADMIN
CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    ruolo ENUM('admin','utente')
);

CREATE TABLE luoghi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE
);

INSERT INTO luoghi(nome) VALUES ('Dispensa');

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

INSERT INTO utenti (username, password, ruolo)
VALUES ('admin', SHA2('admin',256), 'admin');