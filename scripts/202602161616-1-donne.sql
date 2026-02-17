-- =====================================
-- DONNÉES DE TEST RÉALISTES – BNGRC
-- Projet final S3 – Février 2026
-- =====================================

USE db_s2_ETU003339;

-- ======================
-- 1. RÉGIONS de Madagascar
-- ======================
INSERT INTO region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Atsinanana'),
('Boeny'),
('DIANA'),
('Atsimo-Andrefana'),
('Analanjirofo'),
('SAVA');

-- ======================
-- 2. VILLES sinistrées
-- ======================
INSERT INTO villes (nom, region_id) VALUES
('Antananarivo', 1),
('Ambohimangakely', 1),
('Antsirabe', 2),
('Ambatolampy', 2),
('Toamasina', 3),
('Brickaville', 3),
('Mahajanga', 4),
('Marovoay', 4),
('Antsiranana', 5),
('Ambanja', 5),
('Toliara', 6),
('Morondava', 6),
('Fénérive-Est', 7),
('Maroantsetra', 7),
('Antalaha', 8),
('Sambava', 8);

-- ======================
-- 3. TYPES DE BESOIN
-- ======================
INSERT INTO type_besoin (code, libelle) VALUES
('NAT', 'En nature'),
('MAT', 'En matériaux'),
('ARG', 'En argent');

-- ======================
-- 4. BESOINS des sinistrés par ville
-- ======================

-- Toamasina (cyclone)
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(5, 1, 'Riz (sac 50kg)', 95000.00, 200, 200),
(5, 1, 'Huile alimentaire (bidon 20L)', 68000.00, 100, 100),
(5, 2, 'Tôle galvanisée (feuille)', 45000.00, 500, 500),
(5, 2, 'Clous (kg)', 8000.00, 300, 300),
(5, 3, 'Aide financière urgente', 1.00, 5000000, 5000000);

-- Brickaville (inondation)
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(6, 1, 'Riz (sac 50kg)', 95000.00, 150, 150),
(6, 1, 'Eau potable (pack 6L)', 3500.00, 500, 500),
(6, 2, 'Bâche plastique (rouleau)', 35000.00, 80, 80),
(6, 3, 'Aide financière urgente', 1.00, 3000000, 3000000);

-- Maroantsetra (cyclone)
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(14, 1, 'Riz (sac 50kg)', 95000.00, 300, 300),
(14, 1, 'Sucre (kg)', 5000.00, 200, 200),
(14, 1, 'Huile alimentaire (bidon 20L)', 68000.00, 80, 80),
(14, 2, 'Tôle galvanisée (feuille)', 45000.00, 400, 400),
(14, 2, 'Bois de charpente (lot)', 120000.00, 50, 50),
(14, 3, 'Aide financière reconstruction', 1.00, 8000000, 8000000);

-- Antalaha
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(15, 1, 'Riz (sac 50kg)', 95000.00, 100, 100),
(15, 2, 'Tôle galvanisée (feuille)', 45000.00, 200, 200),
(15, 2, 'Ciment (sac 50kg)', 32000.00, 150, 150);

-- Mahajanga
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(7, 1, 'Riz (sac 50kg)', 95000.00, 80, 80),
(7, 1, 'Conserves alimentaires (carton)', 42000.00, 60, 60),
(7, 2, 'Tôle galvanisée (feuille)', 45000.00, 150, 150);

-- Fénérive-Est
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(13, 1, 'Riz (sac 50kg)', 95000.00, 120, 120),
(13, 1, 'Huile alimentaire (bidon 20L)', 68000.00, 50, 50),
(13, 2, 'Clous (kg)', 8000.00, 100, 100),
(13, 3, 'Aide financière urgente', 1.00, 2000000, 2000000);

-- Morondava
INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante) VALUES
(12, 1, 'Riz (sac 50kg)', 95000.00, 60, 60),
(12, 2, 'Bâche plastique (rouleau)', 35000.00, 40, 40);

-- ======================
-- 5. DONATEURS
-- ======================
INSERT INTO donateurs (nom, prenom, email, telephone) VALUES
('Rakoto', 'Jean', 'jean.rakoto@gmail.com', '034 12 345 67'),
('Randria', 'Marie', 'marie.randria@yahoo.fr', '033 45 678 90'),
('Rasoana', 'Hery', 'hery.rasoana@outlook.com', '032 11 222 33'),
('Andria', 'Faly', 'faly.andria@gmail.com', '034 99 888 77'),
('Rabe', 'Voahirana', 'voahirana.rabe@gmail.com', '033 66 555 44'),
('Razafy', 'Patrick', 'patrick.razafy@hotmail.com', '032 77 111 22'),
('ONG Solidarité Madagascar', 'Admin', 'contact@solidarite-mada.org', '020 22 333 44'),
('Croix-Rouge Madagascar', 'Bureau', 'dons@croixrouge.mg', '020 22 600 00'),
('Société STAR', 'Direction RSE', 'rse@star.mg', '020 22 200 10'),
('Ambassade de France', 'Coopération', 'cooperation@ambafrance-mada.org', '020 22 399 00');

-- ======================
-- 6. DONS
-- ======================

-- Dons en nature
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(7, 1, 'Riz (sac 50kg)', 100, NULL, '2026-02-10 08:30:00'),
(8, 1, 'Riz (sac 50kg)', 150, NULL, '2026-02-10 10:00:00'),
(1, 1, 'Huile alimentaire (bidon 20L)', 30, NULL, '2026-02-11 09:15:00'),
(7, 1, 'Eau potable (pack 6L)', 200, NULL, '2026-02-11 11:00:00'),
(9, 1, 'Conserves alimentaires (carton)', 50, NULL, '2026-02-11 14:30:00'),
(2, 1, 'Sucre (kg)', 80, NULL, '2026-02-12 08:00:00'),
(8, 1, 'Riz (sac 50kg)', 200, NULL, '2026-02-13 09:00:00');

-- Dons en matériaux
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(9, 2, 'Tôle galvanisée (feuille)', 300, NULL, '2026-02-10 09:00:00'),
(10, 2, 'Tôle galvanisée (feuille)', 200, NULL, '2026-02-11 10:30:00'),
(3, 2, 'Clous (kg)', 150, NULL, '2026-02-12 07:45:00'),
(4, 2, 'Bâche plastique (rouleau)', 60, NULL, '2026-02-12 10:00:00'),
(7, 2, 'Bois de charpente (lot)', 20, NULL, '2026-02-13 08:30:00'),
(10, 2, 'Ciment (sac 50kg)', 80, NULL, '2026-02-14 09:00:00');

-- Dons en argent
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(10, 3, 'Aide financière cyclone', NULL, 5000000.00, '2026-02-10 11:00:00'),
(5, 3, 'Don personnel', NULL, 500000.00, '2026-02-11 16:00:00'),
(6, 3, 'Contribution solidaire', NULL, 200000.00, '2026-02-12 12:30:00'),
(9, 3, 'Mécénat entreprise STAR', NULL, 3000000.00, '2026-02-13 10:00:00'),
(8, 3, 'Fonds urgence Croix-Rouge', NULL, 4000000.00, '2026-02-14 08:00:00');

