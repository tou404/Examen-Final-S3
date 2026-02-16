<?php

class DispatchController
{
    public static function index()
    {
        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches' => $dispatches,
            'message'    => '',
        ]);
    }

    public static function simuler()
    {
        $nb = DispatchModel::simulerDispatch();

        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches' => $dispatches,
            'message'    => $nb > 0
                ? "$nb attribution(s) effectuée(s) avec succès !"
                : "Aucune nouvelle attribution possible (tous les dons sont déjà dispatchés ou il n'y a pas de besoins à couvrir).",
        ]);
    }
}
