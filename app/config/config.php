<?php

/**
 * Configuration générale de l'application
 */

// Chargement de la configuration de la base de données
$db_config = require __DIR__ . '/database.php';

// Connexion PDO à la base de données
try {
    $dsn = "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['dbname']};charset={$db_config['charset']}";

    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Enregistrer la connexion PDO dans Flight
Flight::register('db', 'PDO', [$dsn, $db_config['user'], $db_config['pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]]);

// Configurer le dossier des vues
Flight::set('flight.views.path', __DIR__ . '/../views');
