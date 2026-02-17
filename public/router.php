<?php

/**
 * Router pour le serveur PHP intégré (php -S)
 * Redirige toutes les requêtes vers index.php sauf les fichiers statiques
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Si le fichier existe physiquement, le servir directement
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Sinon, envoyer vers index.php (FlightPHP router)
require_once __DIR__ . '/index.php';
