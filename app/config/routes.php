<?php

/**
 * Définition des routes de l'application
 */

// Inclusion des modèles
require_once __DIR__ . '/../models/RegionModel.php';
require_once __DIR__ . '/../models/VilleModel.php';
require_once __DIR__ . '/../models/TypeBesoinModel.php';
require_once __DIR__ . '/../models/BesoinModel.php';
require_once __DIR__ . '/../models/DonateurModel.php';
require_once __DIR__ . '/../models/DonModel.php';
require_once __DIR__ . '/../models/DispatchModel.php';

// Inclusion des contrôleurs
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/VilleController.php';
require_once __DIR__ . '/../controllers/BesoinController.php';
require_once __DIR__ . '/../controllers/DonController.php';
require_once __DIR__ . '/../controllers/DispatchController.php';

// ─── Dashboard ───
Flight::route('GET /', ['DashboardController', 'index']);

// ─── Villes ───
Flight::route('GET /ville', ['VilleController', 'index']);
Flight::route('POST /ville', ['VilleController', 'store']);

// ─── Besoins ───
Flight::route('GET /besoin', ['BesoinController', 'index']);
Flight::route('POST /besoin', ['BesoinController', 'store']);

// ─── Dons ───
Flight::route('GET /don', ['DonController', 'index']);
Flight::route('POST /don', ['DonController', 'store']);

// ─── Dispatch ───
Flight::route('GET /dispatch', ['DispatchController', 'index']);
Flight::route('GET /dispatch/simuler', ['DispatchController', 'simuler']);
