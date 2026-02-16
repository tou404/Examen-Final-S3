<?php

/**
 * Définition des routes de l'application
 */

// Inclusion des modèles et contrôleurs nécessaires
require_once __DIR__ . '/../models/RegionModel.php';
require_once __DIR__ . '/../models/VilleModel.php';
require_once __DIR__ . '/../models/TypeBesoinModel.php';
require_once __DIR__ . '/../models/BesoinModel.php';
require_once __DIR__ . '/../models/DonModel.php';
require_once __DIR__ . '/../models/DispatchModel.php';

require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/VilleController.php';
require_once __DIR__ . '/../controllers/BesoinController.php';
require_once __DIR__ . '/../controllers/DonController.php';

// Page d'accueil - Tableau de bord
Flight::route('GET /', ['DashboardController', 'index']);

// Gestion des villes
Flight::route('GET /ville', ['VilleController', 'index']);
Flight::route('POST /ville', ['VilleController', 'store']);

// Gestion des besoins
Flight::route('GET /besoin', ['BesoinController', 'index']);
Flight::route('POST /besoin', ['BesoinController', 'store']);

// Gestion des dons
Flight::route('GET /don', ['DonController', 'index']);
Flight::route('POST /don', ['DonController', 'store']);

?>