<?php

class DashboardController
{
    public static function index()
    {
        // Récupération de la situation par ville (besoins, dons attribués, reste)
        if (class_exists('DispatchModel')) {
            $situation = DispatchModel::getSituationParVille();
        } else {
            $situation = [];
        }

        Flight::render('dashboard.php', [
            'situation' => $situation,
        ]);
    }
}
