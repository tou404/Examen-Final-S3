<?php

class DashboardController
{
    public static function index()
    {
        $situation = DispatchModel::getSituationParVille();

        Flight::render('dashboard.php', [
            'situation' => $situation,
        ]);
    }
}
