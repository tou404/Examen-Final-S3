<?php

/**
 * Définition des routes de l'application
 */

// Page d'accueil - Tableau de bord
Flight::route('/', function () {
    echo '<h1>Bienvenue - BNGRC Suivi des dons</h1>';
    echo '<p>Application de suivi des collectes et des distributions de dons pour les sinistrés</p>';
});




?>