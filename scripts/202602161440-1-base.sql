    -- =====================================
    -- BASE DE DONNÉES : BNGRC
    -- Projet final S3 – Février 2026
    -- =====================================

    DROP DATABASE IF EXISTS db_s2_ETU003339;
    CREATE DATABASE db_s2_ETU003339;
    USE db_s2_ETU003339;

    drop table if exists region;
    drop table if exists villes;
    drop table if exists type_besoin;
    drop table if exists besoin;
    drop table if exists donateurs;
    drop table if exists dons;
    drop table if exists dispatch;
    drop table if exists config;
    drop table if exists achats;
    -- ======================
    -- 1. REGION
    -- ======================
    CREATE TABLE if not exists region (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL
    );

    -- ======================
    -- 2. VILLES
    -- ======================
    CREATE TABLE if not exists villes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        region_id INT,
        FOREIGN KEY (region_id) REFERENCES region(id)
    );

    -- ======================
    -- 3. TYPE DE BESOIN
    -- ======================
    CREATE TABLE if not exists type_besoin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        libelle VARCHAR(255) NOT NULL
    );

    -- ======================
    -- 4. BESOINS
    -- ======================
    CREATE TABLE if not exists besoin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_besoin_id INT NOT NULL,
        ville_id INT NOT NULL,
        description TEXT,
        prix_unitaire DECIMAL(10,2) NOT NULL,
        quantite INT NOT NULL,
        quantite_restante INT NOT NULL,
        ordre INT NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (type_besoin_id) REFERENCES type_besoin(id),
        FOREIGN KEY (ville_id) REFERENCES villes(id)
    );

    -- ======================
    -- 5. DONATEURS
    -- ======================
    CREATE TABLE if not exists donateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL
    
    );
        
    -- ======================
    -- 6. DONS
    -- ======================
    CREATE TABLE if not exists dons (
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
    -- 7. DISPATCH (ATTRIBUTION DES DONS)
    -- ======================
    CREATE TABLE if not exists dispatch (
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
    -- 8. CONFIGURATION (Frais d'achat, etc.)
    -- ======================
    CREATE TABLE if not exists config (
        cle VARCHAR(50) PRIMARY KEY,
        valeur VARCHAR(255) NOT NULL,
        description VARCHAR(255)
    );

    -- Insertion du frais d'achat par défaut (10%)

    -- ======================
    -- 9. ACHATS (Achats de besoins via dons en argent)
    -- ======================
    CREATE TABLE if not exists achats (
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

    INSERT INTO config (cle, valeur, description) VALUES 
    ('frais_achat', '10', 'Pourcentage de frais d''achat sur les achats via dons en argent');
