-- =====================================
-- DONNÉES : BNGRC
-- Projet final S3 – Février 2026
-- =====================================

USE db_s2_ETU003339;

INSERT INTO region (nom) VALUES
('Atsinanana'),
('Vatovavy'),
('Anosy'),
('Boeny'),
('Menabe');


INSERT INTO villes (nom, region_id) VALUES
('Toamasina', 1),
('Mananjary', 2),
('Farafangana', 3),
('Nosy Be', 4),
('Morondava', 5);


INSERT INTO type_besoin (code, libelle) VALUES
('nature', 'Besoin en nature'),
('materiel', 'Besoin en matériel'),
('argent', 'Besoin en argent');


INSERT INTO besoin 
(type_besoin_id, ville_id, description, prix_unitaire, quantite, quantite_restante, ordre, date_creation)
VALUES
-- TOAMASINA
(1, 1, 'Riz (kg)', 3000, 800, 800, 17, '2026-02-16'),
(1, 1, 'Eau (L)', 1000, 1500, 1500, 4, '2026-02-15'),
(2, 1, 'Tôle', 25000, 120, 120, 23, '2026-02-16'),
(2, 1, 'Bâche', 15000, 200, 200, 1, '2026-02-15'),
(3, 1, 'Argent', 1, 12000000, 12000000, 12, '2026-02-16'),

-- MANANJARY
(1, 2, 'Riz (kg)', 3000, 500, 500, 9, '2026-02-15'),
(1, 2, 'Huile (L)', 6000, 120, 120, 25, '2026-02-16'),
(2, 2, 'Tôle', 25000, 80, 80, 6, '2026-02-15'),
(2, 2, 'Clous (kg)', 8000, 60, 60, 19, '2026-02-16'),
(3, 2, 'Argent', 1, 6000000, 6000000, 3, '2026-02-15'),

-- FARAFANGANA
(1, 3, 'Riz (kg)', 3000, 600, 600, 21, '2026-02-16'),
(1, 3, 'Eau (L)', 1000, 1000, 1000, 14, '2026-02-15'),
(2, 3, 'Bâche', 15000, 150, 150, 8, '2026-02-16'),
(2, 3, 'Bois', 10000, 100, 100, 26, '2026-02-15'),
(3, 3, 'Argent', 1, 8000000, 8000000, 10, '2026-02-16'),

-- NOSY BE
(1, 4, 'Riz (kg)', 3000, 300, 300, 5, '2026-02-15'),
(1, 4, 'Haricots', 4000, 200, 200, 18, '2026-02-16'),
(2, 4, 'Tôle', 25000, 40, 40, 2, '2026-02-15'),
(2, 4, 'Clous (kg)', 8000, 30, 30, 24, '2026-02-16'),
(3, 4, 'Argent', 1, 4000000, 4000000, 7, '2026-02-15'),

-- MORONDAVA
(1, 5, 'Riz (kg)', 3000, 700, 700, 11, '2026-02-16'),
(1, 5, 'Eau (L)', 1000, 1200, 1200, 20, '2026-02-15'),
(2, 5, 'Bâche', 15000, 180, 180, 15, '2026-02-16'),
(2, 5, 'Bois', 10000, 150, 150, 22, '2026-02-15'),
(3, 5, 'Argent', 1, 10000000, 10000000, 13, '2026-02-16'),

-- CAS SPÉCIAL
(2, 1, 'Groupe', 6750000, 3, 3, 16, '2026-02-15');


-- ======================
-- FIN DES DONNÉES
-- ======================