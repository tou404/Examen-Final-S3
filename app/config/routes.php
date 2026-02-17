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
require_once __DIR__ . '/../models/ConfigModel.php';
require_once __DIR__ . '/../models/AchatModel.php';
require_once __DIR__ . '/../models/DistributionModel.php';

// Inclusion des contrôleurs
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/VilleController.php';
require_once __DIR__ . '/../controllers/BesoinController.php';
require_once __DIR__ . '/../controllers/DonController.php';
require_once __DIR__ . '/../controllers/DispatchController.php';
require_once __DIR__ . '/../controllers/AchatController.php';
require_once __DIR__ . '/../controllers/RecapController.php';
require_once __DIR__ . '/../controllers/DistributionController.php';

// ─── Dashboard ───
Flight::route('GET /', ['DashboardController', 'index']);

// ─── Villes ───
Flight::route('GET /ville', ['VilleController', 'index']);
Flight::route('POST /ville', ['VilleController', 'store']);
Flight::route('POST /ville/update', ['VilleController', 'update']);
Flight::route('GET /ville/delete/@id', ['VilleController', 'delete']);

// ─── Besoins ───
Flight::route('GET /besoin', ['BesoinController', 'index']);
Flight::route('POST /besoin', ['BesoinController', 'store']);
Flight::route('POST /besoin/update', ['BesoinController', 'update']);
Flight::route('GET /besoin/delete/@id', ['BesoinController', 'delete']);

// ─── Dons ───
Flight::route('GET /don', ['DonController', 'index']);
Flight::route('POST /don', ['DonController', 'store']);
Flight::route('POST /don/update', ['DonController', 'update']);
Flight::route('GET /don/delete/@id', ['DonController', 'delete']);

// ─── Dispatch ───
Flight::route('GET /dispatch', ['DispatchController', 'index']);
Flight::route('GET /dispatch/simuler', ['DispatchController', 'simuler']);
Flight::route('GET /dispatch/valider', ['DispatchController', 'valider']);

// ─── Achats ───
Flight::route('GET /achat', ['AchatController', 'index']);
Flight::route('POST /achat', ['AchatController', 'store']);
Flight::route('POST /achat/frais', ['AchatController', 'updateFrais']);

// ─── Distribution ───
Flight::route('GET /distribution', ['DistributionController', 'index']);
Flight::route('POST /distribution', ['DistributionController', 'store']);
Flight::route('GET /distribution/delete/@id', ['DistributionController', 'delete']);

// ─── Récapitulation ───
Flight::route('GET /recap', ['RecapController', 'index']);
Flight::route('GET /api/recap', ['RecapController', 'api']);
