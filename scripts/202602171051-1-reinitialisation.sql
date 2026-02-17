-- =====================================
-- SCRIPT DE REINITIALISATION (SANS DROP DATABASE)
-- =====================================

USE db_s2_ETU003339;

-- 1. Désactiver les contraintes de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Supprimer les tables (ordre sécurisé)
DROP TABLE IF EXISTS achats;
DROP TABLE IF EXISTS dispatch;
DROP TABLE IF EXISTS dons;
DROP TABLE IF EXISTS besoin;
DROP TABLE IF EXISTS villes;
DROP TABLE IF EXISTS type_besoin;
DROP TABLE IF EXISTS donateurs;
DROP TABLE IF EXISTS config;
DROP TABLE IF EXISTS region;

-- 3. Réactiver les contraintes
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================
-- RECREATION DES TABLES
-- =====================================

-- ======================
-- 1. REGION
-- ======================
CREATE TABLE IF NOT EXISTS region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- ======================
-- 2. VILLES
-- ======================
CREATE TABLE IF NOT EXISTS villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES region(id)
);

-- ======================
-- 3. TYPE DE BESOIN
-- ======================
CREATE TABLE IF NOT EXISTS type_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL,
    libelle VARCHAR(255) NOT NULL
);

-- ======================
-- 4. BESOINS
-- ======================
CREATE TABLE IF NOT EXISTS besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_besoin_id INT NOT NULL,
    ville_id INT NOT NULL,
    description TEXT,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    quantite INT NOT NULL,
    quantite_restante INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_besoin_id) REFERENCES type_besoin(id),
    FOREIGN KEY (ville_id) REFERENCES villes(id)
);

-- ======================
-- 5. DONATEURS
-- ======================
CREATE TABLE IF NOT EXISTS donateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20)
);

-- ======================
-- 6. DONS
-- ======================
CREATE TABLE IF NOT EXISTS dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donateur_id INT NOT NULL,
    type_besoin_id INT NOT NULL,
    designation VARCHAR(255),
    quantite INT DEFAULT NULL,
    montant DECIMAL(10,2) DEFAULT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donateur_id) REFERENCES donateurs(id),
    FOREIGN KEY (type_besoin_id) REFERENCES type_besoin(id)
);

-- ======================
-- 7. DISPATCH
-- ======================
CREATE TABLE IF NOT EXISTS dispatch (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee INT DEFAULT NULL,
    montant_attribue DECIMAL(10,2) DEFAULT NULL,
    date_dispatch DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id),
    FOREIGN KEY (besoin_id) REFERENCES besoin(id)
);

-- ======================
-- 8. CONFIG
-- ======================
CREATE TABLE IF NOT EXISTS config (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);

-- ======================
-- 9. ACHATS
-- ======================
CREATE TABLE IF NOT EXISTS achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    don_id INT NOT NULL,
    quantite_achetee INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    montant_ht DECIMAL(10,2) NOT NULL,
    frais_pourcentage DECIMAL(5,2) NOT NULL,
    montant_frais DECIMAL(10,2) NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id),
    FOREIGN KEY (don_id) REFERENCES dons(id)
);

-- 10. Réinsertion de la config par défaut
INSERT INTO config (cle, valeur, description)
VALUES ('frais_achat', '10', 'Pourcentage de frais d''achat sur les achats via dons en argent');
