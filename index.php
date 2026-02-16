<?php

/**
 * Point d'entrée de l'application
 */

require 'vendor/autoload.php';

// Chargement de la configuration
require 'app/config/config.php';

// Chargement des routes
require 'app/config/routes.php';

// Démarrage de l'application
Flight::start();

?>
