-- =====================================
-- DONNÉES DE TEST : BNGRC
-- Suivi des collectes et distributions de dons pour les sinistrés
-- Données conçues pour montrer la différence entre les 3 modes de dispatch
-- Projet final S3 – Février 2026
-- =====================================

USE db_s2_ETU003339;

-- ======================
-- 1. REGIONS (id 1-4)
-- ======================
INSERT INTO region (nom) VALUES
('Analamanga'),       -- 1
('Atsinanana'),       -- 2
('Boeny'),            -- 3
('Anosy');             -- 4

-- ======================
-- 2. VILLES (id 1-5)
-- ======================
INSERT INTO villes (nom, region_id) VALUES
('Antananarivo', 1),   -- 1
('Toamasina', 2),      -- 2
('Mahajanga', 3),      -- 3
('Taolagnaro', 4),     -- 4
('Ambovombe', 4);      -- 5

-- ======================
-- 3. TYPES DE BESOIN (id 1-3)
-- ======================
INSERT INTO type_besoin (code, libelle) VALUES
('NAT', 'Nature'),       -- 1
('MAT', 'Matériaux'),    -- 2
('ARG', 'Argent');        -- 3

-- ======================
-- 4. BESOINS
-- ======================
-- *** NATURE (type 1) ***
-- Besoins en riz : 3 villes demandent du riz avec des quantités et dates différentes
-- Total demandé = 50 + 30 + 10 = 90, mais on va donner seulement 45
-- → Mode ordre    : Tana(1er fev)=45, Toam=0, Mahaj=0 (1er servi, 1er comblé)
-- → Mode min_need : Mahaj(10)=10, Toam(30)=30, Tana(5 restant)=5
-- → Mode proportionnel : Tana=50/90*45=25, Toam=30/90*45=15, Mahaj=10/90*45=5

INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
-- Antananarivo demande 50 riz en 1er (1er février)
(1, 1, 'Riz (sac 25kg)', 50000.00, 50, 50, '2026-02-01 08:00:00'),
-- Toamasina demande 30 riz en 2e (3 février)
(1, 2, 'Riz (sac 25kg)', 50000.00, 30, 30, '2026-02-03 08:00:00'),
-- Mahajanga demande 10 riz en 3e (5 février)
(1, 3, 'Riz (sac 25kg)', 50000.00, 10, 10, '2026-02-05 08:00:00'),

-- Besoins huile : 2 villes
(1, 1, 'Huile alimentaire (bidon 5L)', 25000.00, 20, 20, '2026-02-01 09:00:00'),
(1, 4, 'Huile alimentaire (bidon 5L)', 25000.00, 15, 15, '2026-02-02 09:00:00'),

-- Besoin sucre : 1 ville
(1, 5, 'Sucre (sac 10kg)', 30000.00, 25, 25, '2026-02-04 08:00:00');

-- *** MATÉRIAUX (type 2) ***
-- Tôles : 3 villes, total demandé = 40+25+15 = 80, on donne 50
-- → Mode ordre    : Toam(40)=40, Taol=10 restant, Ambov=0
-- → Mode min_need : Ambov(15)=15, Taol(25)=25, Toam=10 restant
-- → Mode proportionnel : Toam=40/80*50=25, Taol=25/80*50=15.6→16, Ambov=15/80*50=9.3→9

INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
-- Toamasina demande 40 tôles en 1er (2 février)
(2, 2, 'Tôles ondulées', 55000.00, 40, 40, '2026-02-02 08:00:00'),
-- Taolagnaro demande 25 tôles en 2e (4 février)
(2, 4, 'Tôles ondulées', 55000.00, 25, 25, '2026-02-04 08:00:00'),
-- Ambovombe demande 15 tôles en 3e (6 février)
(2, 5, 'Tôles ondulées', 55000.00, 15, 15, '2026-02-06 08:00:00'),

-- Clous : 2 villes
(2, 3, 'Clous (kg)', 12000.00, 30, 30, '2026-02-03 09:00:00'),
(2, 1, 'Clous (kg)', 12000.00, 20, 20, '2026-02-05 09:00:00'),

-- Bâches : 1 ville
(2, 2, 'Bâches de protection', 45000.00, 20, 20, '2026-02-01 10:00:00');

-- *** ARGENT (type 3) ***
-- 3 villes demandent de l'argent, total = 5M + 3M + 1M = 9M, on donne 5M
-- Exactement l'exemple du todo ! 5/9*5M=2.77M, 3/9*5M=1.66M, 1/9*5M=0.55M
-- → Mode ordre    : Tana(5M)=5M, Toam=0, Mahaj=0
-- → Mode min_need : Mahaj(1M)=1M, Toam(3M)=3M, Tana=1M restant
-- → Mode proportionnel : Tana=2 777 778, Toam=1 666 667, Mahaj=555 555

INSERT INTO besoin (type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, date_creation) VALUES
-- Antananarivo demande 5M en 1er (1er février)
(3, 1, 'Fonds médicaux urgents', 5000000.00, 1, 1, '2026-02-01 10:00:00'),
-- Toamasina demande 3M en 2e (3 février)
(3, 2, 'Fonds reconstruction', 3000000.00, 1, 1, '2026-02-03 10:00:00'),
-- Mahajanga demande 1M en 3e (5 février)
(3, 3, 'Aide familles déplacées', 1000000.00, 1, 1, '2026-02-05 10:00:00');

-- ======================
-- 5. DONATEURS (id 1-6)
-- ======================
INSERT INTO donateurs (nom, prenom, email, telephone) VALUES
('RAKOTO', 'Jean', 'jean.rakoto@gmail.com', '034 12 345 67'),
('RANDRIA', 'Marie', 'marie.randria@yahoo.fr', '033 23 456 78'),
('RASOA', 'Pierre', 'pierre.rasoa@outlook.com', '032 34 567 89'),
('RAHARISON', 'Claire', 'claire.raharison@gmail.com', '034 45 678 90'),
('CORPORATION TELMA', 'Service RSE', 'rse@telma.mg', '020 22 200 00'),
('ONG CARE', 'Coordination', 'contact@care.mg', '020 22 300 00');

-- ======================
-- 6. DONS
-- ======================
-- Clé : les dons sont INSUFFISANTS pour couvrir tous les besoins
-- C'est cette pénurie qui fait apparaître la différence entre les 3 modes

-- DON NATURE : 45 sacs de riz (besoins total = 90) → pénurie !
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(1, 1, 'Riz (sac 25kg)', 45, NULL, '2026-02-10 08:00:00');

-- DON NATURE : 12 bidons d'huile (besoin total = 35) → pénurie !
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(2, 1, 'Huile alimentaire (bidon 5L)', 12, NULL, '2026-02-10 10:00:00');

-- DON NATURE : 25 sacs de sucre (besoin = 25) → juste assez
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(3, 1, 'Sucre (sac 10kg)', 25, NULL, '2026-02-10 12:00:00');

-- DON MATÉRIAUX : 50 tôles (besoin total = 80) → pénurie !
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(4, 2, 'Tôles ondulées', 50, NULL, '2026-02-11 08:00:00');

-- DON MATÉRIAUX : 30 kg clous (besoin total = 50) → pénurie !
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(5, 2, 'Clous (kg)', 30, NULL, '2026-02-11 10:00:00');

-- DON MATÉRIAUX : 20 bâches (besoin = 20) → juste assez
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(6, 2, 'Bâches de protection', 20, NULL, '2026-02-11 12:00:00');

-- DON ARGENT : 5 000 000 Ar (besoin total = 9M) → pénurie !
-- Comme l'exemple du todo : distribuer 5M entre besoins de 5M, 3M, 1M
INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant, date_don) VALUES
(5, 3, 'Fonds RSE TELMA', NULL, 5000000.00, '2026-02-12 08:00:00');

-- ======================
-- PAS DE DISPATCH INITIALEMENT
-- Le dispatch se fait via la simulation dans l'application
-- On pourra voir clairement la différence entre les 3 modes :
--
-- ══════════════════════════════════════════════════════════════════════
-- EXEMPLE : 45 sacs de riz → Tana(50), Toamasina(30), Mahajanga(10)
-- ══════════════════════════════════════════════════════════════════════
--
-- MODE 1 (Par ordre de date) :
--   Tana (1er fév) → reçoit 45 (ne couvre que 45/50)
--   Toamasina     → reçoit 0
--   Mahajanga     → reçoit 0
--
-- MODE 2 (Plus petits besoins d'abord) :
--   Mahajanga (10) → reçoit 10
--   Toamasina (30) → reçoit 30
--   Tana      (50) → reçoit 5 (reste)
--
-- MODE 3 (Proportionnel) :
--   Tana : 50/90 × 45 = 25.00 → 25
--   Toam : 30/90 × 45 = 15.00 → 15
--   Mahaj: 10/90 × 45 =  5.00 →  5
--   Total = 45 ✓
--
-- ══════════════════════════════════════════════════════════════════════
-- EXEMPLE : 5 000 000 Ar → Tana(5M), Toam(3M), Mahaj(1M)
-- ══════════════════════════════════════════════════════════════════════
--
-- MODE 1 (Par ordre) :
--   Tana (1er fév) → reçoit 5 000 000 (couvert)
--   Toamasina      → reçoit 0
--   Mahajanga      → reçoit 0
--
-- MODE 2 (Plus petits d'abord) :
--   Mahajanga (1M) → reçoit 1 000 000
--   Toamasina (3M) → reçoit 3 000 000
--   Tana      (5M) → reçoit 1 000 000 (reste)
--
-- MODE 3 (Proportionnel) :
--   Tana : 5/9 × 5M = 2 777 778
--   Toam : 3/9 × 5M = 1 666 667
--   Mahaj: 1/9 × 5M =   555 555
--   Total = 5 000 000 ✓
-- ======================

-- ======================
-- FIN DES DONNÉES
-- ======================
