<?php

/**
 * Point d'entrée de l'application (dossier public)
 */

require __DIR__ . '/../vendor/autoload.php';

// Chargement de la configuration
require __DIR__ . '/../app/config/config.php';

// Chargement des routes
require __DIR__ . '/../app/config/routes.php';

// Démarrage de l'application
Flight::start();
