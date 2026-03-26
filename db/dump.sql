

USE azienda_agricola;

INSERT INTO categorie (nome) VALUES
('Frutta fresca'),
('Verdura fresca'),
('Olio e condimenti'),
('Miele e derivati'),
('Marmellate e conserve'),
('Frutta secca'),
('Distillati e liquori'),
('Piante aromatiche'),
('Saponi e unguenti');

INSERT INTO luoghi (nome, tipo) VALUES
('Cantina',             'magazzino'),
('Serra',               'produzione'),
('Laboratorio',         'produzione'),
('Punto vendita',       'vendita'),
('Mercato settimanale', 'vendita');


INSERT INTO prodotti (nome, tipo, unita, disponibile, id_categoria) VALUES
-- Frutta
('Fichi',                    'fresco',       'kg',    TRUE, 1),
('Melograni',                'fresco',       'pezzo', TRUE, 1),
('Uva da tavola',            'fresco',       'kg',    TRUE, 1),
('Cachi',                    'fresco',       'pezzo', TRUE, 1),
-- Verdura
('Pomodori San Marzano',     'fresco',       'kg',    TRUE, 2),
('Peperoni',                 'fresco',       'kg',    TRUE, 2),
('Zucchine',                 'fresco',       'kg',    TRUE, 2),
('Melanzane',                'fresco',       'kg',    TRUE, 2),
-- Condimenti
('Olio EVO',                 'riserva',      'litro', TRUE, 3),
('Olio al peperoncino',      'confezionato', 'pezzo', TRUE, 3),
('Aceto di vino rosso',      'riserva',      'litro', TRUE, 3),
-- Derivati
('Miele millefiori',         'riserva',      'kg',    TRUE, 4),
('Miele di arancio',         'riserva',      'kg',    TRUE, 4),
('Miele di castagno',        'riserva',      'kg',    TRUE, 4),
('Propoli tintura',          'confezionato', 'pezzo', TRUE, 4),
('Cera d''api naturale',     'confezionato', 'pezzo', TRUE, 4),
-- Conserve
('Marmellata di fichi',      'confezionato', 'pezzo', TRUE, 5),
('Marmellata di arance',     'confezionato', 'pezzo', TRUE, 5),
('Passata di pomodoro',      'confezionato', 'pezzo', TRUE, 5),
('Peperoni sott''olio',      'confezionato', 'pezzo', TRUE, 5),
('Melanzane grigliate',      'confezionato', 'pezzo', TRUE, 5),
-- Frutta secca
('Mandorle sgusciate',       'riserva',      'kg',    TRUE, 6),
('Mandorle con guscio',      'fresco',       'kg',    TRUE, 6),
('Noci',                     'fresco',       'kg',    TRUE, 6),
('Fichi secchi',             'confezionato', 'pezzo', TRUE, 6),
-- Distillati
('Liquore al fico',          'confezionato', 'pezzo', TRUE, 7),
('Grappa di primitivo',      'riserva',      'litro', TRUE, 7),
-- Piante aromatiche
('Rosmarino essiccato',      'confezionato', 'pezzo', TRUE, 8),
('Origano essiccato',        'confezionato', 'pezzo', TRUE, 8),
('Lavanda essiccata',        'confezionato', 'pezzo', TRUE, 8),
-- Saponi
('Sapone olio d''oliva',     'confezionato', 'pezzo', TRUE, 9),
('Unguento alla calendula',  'confezionato', 'pezzo', TRUE, 9),
('Balsamo alla lavanda',     'confezionato', 'pezzo', TRUE, 9);


INSERT INTO prezzi (id_prodotto, prezzo, data_inizio, data_fine) VALUES
(1,  2.50,  '2024-09-01', NULL),
(2,  1.20,  '2024-10-01', NULL),
(3,  2.00,  '2024-09-15', NULL),
(4,  0.80,  '2024-10-01', NULL),
(5,  1.80,  '2024-07-01', NULL),
(6,  1.50,  '2024-07-01', NULL),
(7,  1.20,  '2024-07-01', NULL),
(8,  1.50,  '2024-07-01', NULL),
(9,  8.00,  '2024-11-01', NULL),
(10, 6.50,  '2024-11-01', NULL),
(11, 4.00,  '2024-01-01', NULL),
(12, 12.00, '2024-06-01', NULL),
(13, 14.00, '2024-06-01', NULL),
(14, 16.00, '2024-06-01', NULL),
(15, 9.00,  '2024-06-01', NULL),
(16, 5.00,  '2024-06-01', NULL),
(17, 4.50,  '2024-09-01', NULL),
(18, 4.00,  '2024-01-01', NULL),
(19, 3.00,  '2024-08-01', NULL),
(20, 5.50,  '2024-08-01', NULL),
(21, 5.00,  '2024-08-01', NULL),
(22, 10.00, '2024-10-01', NULL),
(23, 5.00,  '2024-10-01', NULL),
(24, 8.00,  '2024-10-01', NULL),
(25, 6.00,  '2024-10-01', NULL),
(26, 12.00, '2024-12-01', NULL),
(27, 18.00, '2024-01-01', NULL),
(28, 2.50,  '2024-07-01', NULL),
(29, 2.00,  '2024-07-01', NULL),
(30, 3.00,  '2024-07-01', NULL),
(31, 7.00,  '2024-01-01', NULL),
(32, 8.50,  '2024-01-01', NULL),
(33, 7.50,  '2024-01-01', NULL);


INSERT INTO lavorazioni (id_prodotto, id_luogo, tipo_lavorazione, data_lavorazione, quantita_prodotta, unita_misura) VALUES
(9,  4, 'Frangitura e spremitura',     '2024-11-05', 200.00, 'litro'),
(11, 4, 'Fermentazione e affinamento', '2024-03-10',  50.00, 'litro'),
(12, 4, 'Smielatura',                  '2024-06-20',  80.00, 'kg'),
(13, 4, 'Smielatura',                  '2024-06-25',  40.00, 'kg'),
(14, 4, 'Smielatura',                  '2024-09-10',  25.00, 'kg'),
(22, 4, 'Sgusciatura e selezione',     '2024-10-15',  60.00, 'kg'),
(27, 4, 'Distillazione',               '2024-01-20',  30.00, 'litro');

INSERT INTO riserva (id_prodotto, id_lavorazione, id_luogo, quantita, data_produzione) VALUES
(9,  1, 2, 175.00, '2024-11-05'),
(11, 2, 1,  45.00, '2024-03-10'),
(12, 3, 1,  63.00, '2024-06-20'),
(13, 4, 1,  31.00, '2024-06-25'),
(14, 5, 1,  19.00, '2024-09-10'),
(22, 6, 1,  55.00, '2024-10-15'),
(27, 7, 2,  28.00, '2024-01-20');

INSERT INTO confezioni (id_prodotto, id_riserva, id_luogo, quantita, peso_netto, prezzo, data_produzione_originale, data_confezionamento, giacenza) VALUES
(10, 1,    5, 24, 0.250, 6.50,  '2024-11-05', '2024-11-20', 18),
(17, NULL, 5, 40, 0.350, 4.50,  '2024-09-10', '2024-09-12', 26),
(18, NULL, 5, 35, 0.350, 4.00,  '2024-01-15', '2024-01-16', 20),
(19, NULL, 5, 50, 0.700, 3.00,  '2024-08-20', '2024-08-21', 29),
(20, NULL, 5, 30, 0.300, 5.50,  '2024-08-15', '2024-08-16', 20),
(21, NULL, 5, 25, 0.300, 5.00,  '2024-08-18', '2024-08-19', 17),
(25, NULL, 5, 45, 0.250, 6.00,  '2024-09-20', '2024-09-25', 29),
(26, NULL, 2, 20, 0.500, 12.00, '2024-12-01', '2024-12-05', 12),
(15, NULL, 5, 30, 0.030, 9.00,  '2024-06-20', '2024-06-22', 24),
(16, NULL, 5, 20, 0.100, 5.00,  '2024-06-20', '2024-06-22', 16),
(28, NULL, 5, 60, 0.050, 2.50,  '2024-07-10', '2024-07-15', 46),
(29, NULL, 5, 55, 0.050, 2.00,  '2024-07-10', '2024-07-15', 40),
(30, NULL, 5, 40, 0.050, 3.00,  '2024-07-10', '2024-07-15', 33),
(31, NULL, 5, 50, 0.100, 7.00,  '2024-02-01', '2024-02-05', 36),
(32, NULL, 5, 30, 0.050, 8.50,  '2024-02-01', '2024-02-05', 21),
(33, NULL, 5, 25, 0.050, 7.50,  '2024-02-01', '2024-02-05', 18);



