-- =====================================
-- DONNÉES DE TEST : BNGRC
-- Projet final S3 – Février 2026
-- =====================================

USE db_s2_ETU003339;

-- ======================
-- 1. REGIONS DE MADAGASCAR
-- ======================
INSERT INTO region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Bongolava'),
('Sofia'),
('Boeny'),
('Betsiboka'),
('Melaky'),
('Alaotra-Mangoro'),
('Atsinanana'),
('Analanjirofo'),
('Amoron''i Mania'),
('Haute Matsiatra'),
('Vatovavy'),
('Fitovinany'),
('Atsimo-Atsinanana'),
('Ihorombe'),
('Menabe'),
('Atsimo-Andrefana'),
('Androy'),
('Anosy'),
('Diana'),
('Sava');

-- ======================
-- 2. VILLES PRINCIPALES
-- ======================
INSERT INTO villes (nom, region_id) VALUES
-- Analamanga (1)
('Antananarivo', 1),
('Ankazobe', 1),
('Anjozorobe', 1),
-- Vakinankaratra (2)
('Antsirabe', 2),
('Ambatolampy', 2),
('Betafo', 2),
-- Sofia (5)
('Antsohihy', 5),
('Mandritsara', 5),
('Bealanana', 5),
-- Boeny (6)
('Mahajanga', 6),
('Ambato-Boeni', 6),
('Marovoay', 6),
-- Alaotra-Mangoro (9)
('Ambatondrazaka', 9),
('Moramanga', 9),
('Andilamena', 9),
-- Atsinanana (10)
('Toamasina', 10),
('Brickaville', 10),
('Vatomandry', 10),
-- Haute Matsiatra (13)
('Fianarantsoa', 13),
('Ambalavao', 13),
('Ambohimahasoa', 13),
-- Atsimo-Andrefana (19)
('Toliara', 19),
('Sakaraha', 19),
('Ankazoabo', 19),
-- Androy (20)
('Ambovombe', 20),
('Bekily', 20),
('Beloha', 20),
-- Anosy (21)
('Taolagnaro', 21),
('Amboasary', 21),
('Betroka', 21),
-- Diana (22)
('Antsiranana', 22),
('Nosy Be', 22),
('Ambilobe', 22),
-- Sava (23)
('Sambava', 23),
('Antalaha', 23),
('Vohémar', 23);

-- ======================
-- 3. TYPES DE BESOIN
-- ======================
INSERT INTO type_besoin (code, libelle) VALUES
('NAT', 'Nature (denrées alimentaires)'),
('MAT', 'Matériels'),
('ARG', 'Argent');

-- ======================
-- 4. BESOINS PAR VILLE
-- ======================
-- Les besoins sont créés pour les zones sinistrées

-- Ambovombe (id: 25) - Zone très touchée par la sécheresse
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 25, 'Riz (sac de 50kg)', 95000.00, 500, 500, '2026-02-01 08:00:00'),
(1, 25, 'Huile alimentaire (bidon 20L)', 85000.00, 200, 200, '2026-02-01 08:30:00'),
(1, 25, 'Sucre (sac de 25kg)', 65000.00, 150, 150, '2026-02-01 09:00:00'),
(2, 25, 'Tentes de secours', 250000.00, 100, 100, '2026-02-01 10:00:00'),
(2, 25, 'Couvertures', 35000.00, 500, 500, '2026-02-01 10:30:00'),
(3, 25, 'Fonds médicaux urgents', 1.00, 0, 0, '2026-02-01 11:00:00');

-- Beloha (id: 27) - Zone sinistrée
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 27, 'Riz (sac de 50kg)', 95000.00, 300, 300, '2026-02-02 08:00:00'),
(1, 27, 'Maïs (sac de 50kg)', 45000.00, 200, 200, '2026-02-02 08:30:00'),
(2, 27, 'Bidons d''eau (20L)', 15000.00, 400, 400, '2026-02-02 09:00:00'),
(2, 27, 'Ustensiles de cuisine', 25000.00, 150, 150, '2026-02-02 09:30:00'),
(3, 27, 'Fonds reconstruction', 1.00, 0, 0, '2026-02-02 10:00:00');

-- Taolagnaro (id: 28) - Cyclone récent
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 28, 'Riz (sac de 50kg)', 95000.00, 400, 400, '2026-02-03 08:00:00'),
(1, 28, 'Conserves alimentaires', 8500.00, 1000, 1000, '2026-02-03 08:30:00'),
(2, 28, 'Bâches de protection', 45000.00, 300, 300, '2026-02-03 09:00:00'),
(2, 28, 'Tôles ondulées', 55000.00, 500, 500, '2026-02-03 09:30:00'),
(2, 28, 'Clous (kg)', 12000.00, 200, 200, '2026-02-03 10:00:00'),
(3, 28, 'Aide financière d''urgence', 1.00, 0, 0, '2026-02-03 10:30:00');

-- Toamasina (id: 16) - Inondations
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 16, 'Eau potable (pack 6L)', 6000.00, 2000, 2000, '2026-02-04 08:00:00'),
(1, 16, 'Riz (sac de 50kg)', 95000.00, 350, 350, '2026-02-04 08:30:00'),
(2, 16, 'Médicaments de base', 150000.00, 100, 100, '2026-02-04 09:00:00'),
(2, 16, 'Matelas gonflables', 75000.00, 200, 200, '2026-02-04 09:30:00'),
(3, 16, 'Fonds pour relogement', 1.00, 0, 0, '2026-02-04 10:00:00');

-- Mahajanga (id: 10) - Cyclone
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 10, 'Riz (sac de 50kg)', 95000.00, 250, 250, '2026-02-05 08:00:00'),
(1, 10, 'Farine (sac de 25kg)', 42000.00, 150, 150, '2026-02-05 08:30:00'),
(2, 10, 'Générateurs électriques', 850000.00, 20, 20, '2026-02-05 09:00:00'),
(2, 10, 'Pompes à eau', 450000.00, 15, 15, '2026-02-05 09:30:00'),
(3, 10, 'Reconstruction infrastructure', 1.00, 0, 0, '2026-02-05 10:00:00');

-- Antsirabe (id: 4) - Glissement de terrain
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 4, 'Riz (sac de 50kg)', 95000.00, 200, 200, '2026-02-06 08:00:00'),
(2, 4, 'Pelles et pioches', 35000.00, 100, 100, '2026-02-06 09:00:00'),
(2, 4, 'Brouettes', 125000.00, 50, 50, '2026-02-06 09:30:00'),
(3, 4, 'Aide aux familles déplacées', 1.00, 0, 0, '2026-02-06 10:00:00');

-- Fianarantsoa (id: 19) - Tempête
INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
(1, 19, 'Riz (sac de 50kg)', 95000.00, 180, 180, '2026-02-07 08:00:00'),
(1, 19, 'Légumes secs (sac 10kg)', 28000.00, 200, 200, '2026-02-07 08:30:00'),
(2, 19, 'Bâches plastiques', 45000.00, 150, 150, '2026-02-07 09:00:00'),
(3, 19, 'Aide scolaire enfants', 1.00, 0, 0, '2026-02-07 09:30:00');

-- ======================
-- 5. DONATEURS
-- ======================
INSERT INTO donateurs (nom, prenom, email, telephone) VALUES
('RAKOTO', 'Jean', 'jean.rakoto@gmail.com', '034 12 345 67'),
('RANDRIA', 'Marie', 'marie.randria@yahoo.fr', '033 23 456 78'),
('RASOA', 'Pierre', 'pierre.rasoa@outlook.com', '032 34 567 89'),
('RAHARISON', 'Claire', 'claire.raharison@gmail.com', '034 45 678 90'),
('ANDRIA', 'Paul', 'paul.andria@gmail.com', '033 56 789 01'),
('RABE', 'Sophie', 'sophie.rabe@yahoo.fr', '032 67 890 12'),
('RAZAFY', 'Michel', 'michel.razafy@gmail.com', '034 78 901 23'),
('RASOANAIVO', 'Anne', 'anne.rasoanaivo@outlook.com', '033 89 012 34'),
('RAKOTOMANGA', 'Luc', 'luc.rakotomanga@gmail.com', '032 90 123 45'),
('RANDRIAMANANA', 'Julie', 'julie.randriamanana@yahoo.fr', '034 01 234 56'),
('RABEMANANJARA', 'Eric', 'eric.rabemananjara@gmail.com', '033 12 345 67'),
('RAFANOMEZANA', 'Hery', 'hery.rafanomezana@outlook.com', '032 23 456 78'),
('CORPORATION TELMA', 'Service RSE', 'rse@telma.mg', '020 22 200 00'),
('ONG AIDE MADAGASCAR', 'Coordination', 'contact@aidemadagascar.org', '020 22 300 00'),
('JIRAMA SOLIDAIRE', 'Direction', 'solidaire@jirama.mg', '020 22 400 00');

-- ======================
-- 6. DONS
-- ======================
-- Dons en nature (type_besoin_id = 1)
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(1, 1, 'Riz (sac de 50kg)', 50, NULL, '2026-02-05 10:00:00'),
(2, 1, 'Huile alimentaire (bidon 20L)', 30, NULL, '2026-02-05 14:00:00'),
(3, 1, 'Riz (sac de 50kg)', 25, NULL, '2026-02-06 09:00:00'),
(4, 1, 'Conserves alimentaires', 200, NULL, '2026-02-06 11:00:00'),
(5, 1, 'Sucre (sac de 25kg)', 40, NULL, '2026-02-07 08:00:00'),
(6, 1, 'Maïs (sac de 50kg)', 60, NULL, '2026-02-07 10:00:00'),
(13, 1, 'Riz (sac de 50kg)', 200, NULL, '2026-02-08 09:00:00'),
(14, 1, 'Eau potable (pack 6L)', 500, NULL, '2026-02-08 14:00:00');

-- Dons en matériels (type_besoin_id = 2)
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(7, 2, 'Tentes de secours', 20, NULL, '2026-02-06 15:00:00'),
(8, 2, 'Couvertures', 100, NULL, '2026-02-07 09:00:00'),
(9, 2, 'Bâches de protection', 50, NULL, '2026-02-07 14:00:00'),
(10, 2, 'Bidons d''eau (20L)', 80, NULL, '2026-02-08 10:00:00'),
(11, 2, 'Ustensiles de cuisine', 30, NULL, '2026-02-08 16:00:00'),
(13, 2, 'Tôles ondulées', 150, NULL, '2026-02-09 09:00:00'),
(14, 2, 'Médicaments de base', 25, NULL, '2026-02-09 11:00:00'),
(15, 2, 'Générateurs électriques', 5, NULL, '2026-02-10 08:00:00');

-- Dons en argent (type_besoin_id = 3)
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(1, 3, 'Contribution solidaire', NULL, 500000.00, '2026-02-05 12:00:00'),
(2, 3, 'Don pour les sinistrés', NULL, 250000.00, '2026-02-06 10:00:00'),
(4, 3, 'Aide d''urgence', NULL, 1000000.00, '2026-02-06 16:00:00'),
(5, 3, 'Soutien aux familles', NULL, 350000.00, '2026-02-07 11:00:00'),
(12, 3, 'Don humanitaire', NULL, 750000.00, '2026-02-08 09:00:00'),
(13, 3, 'Fonds RSE TELMA', NULL, 10000000.00, '2026-02-08 10:00:00'),
(14, 3, 'Aide ONG', NULL, 5000000.00, '2026-02-09 09:00:00'),
(15, 3, 'Contribution JIRAMA', NULL, 3000000.00, '2026-02-10 09:00:00');

-- ======================
-- 7. DISPATCHES (EXEMPLES D'ATTRIBUTION)
-- ======================
-- Attribution de quelques dons aux besoins

-- Don de riz de RAKOTO Jean (don_id: 1) vers Ambovombe (besoin_id: 1)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(1, 1, 50, NULL, '2026-02-10 10:00:00');

-- Mise à jour du besoin
UPDATE besoin SET quantite_restante = quantite_restante - 50 WHERE id = 1;

-- Don d'huile de RANDRIA Marie (don_id: 2) vers Ambovombe (besoin_id: 2)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(2, 2, 30, NULL, '2026-02-10 10:30:00');

UPDATE besoin SET quantite_restante = quantite_restante - 30 WHERE id = 2;

-- Don de tentes (don_id: 9) vers Ambovombe (besoin_id: 4)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(9, 4, 20, NULL, '2026-02-10 11:00:00');

UPDATE besoin SET quantite_restante = quantite_restante - 20 WHERE id = 4;

-- Don de couvertures (don_id: 10) vers Ambovombe (besoin_id: 5)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(10, 5, 100, NULL, '2026-02-10 11:30:00');

UPDATE besoin SET quantite_restante = quantite_restante - 100 WHERE id = 5;

-- Don d'argent TELMA (don_id: 22) vers Ambovombe - Fonds médicaux (besoin_id: 6)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(22, 6, NULL, 3000000.00, '2026-02-10 12:00:00');

-- Don de riz TELMA (don_id: 7) vers Beloha (besoin_id: 7)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(7, 7, 150, NULL, '2026-02-11 09:00:00');

UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 7;

-- Don d'eau ONG (don_id: 8) vers Toamasina (besoin_id: 18)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(8, 18, 500, NULL, '2026-02-11 10:00:00');

UPDATE besoin SET quantite_restante = quantite_restante - 500 WHERE id = 18;

-- Don de tôles TELMA (don_id: 14) vers Taolagnaro (besoin_id: 15)
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(14, 15, 150, NULL, '2026-02-11 14:00:00');

UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 15;

-- Don d'argent ONG (don_id: 23) vers plusieurs besoins
INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue, date_dispatch) VALUES
(23, 17, NULL, 2000000.00, '2026-02-12 09:00:00'),
(23, 11, NULL, 1500000.00, '2026-02-12 09:30:00'),
(23, 22, NULL, 1500000.00, '2026-02-12 10:00:00');

-- ======================
-- FIN DES DONNÉES
-- ======================
