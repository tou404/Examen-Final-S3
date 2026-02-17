<?php

class DispatchController
{
    public static function index()
    {
        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches'  => $dispatches,
            'simulation'  => null,
            'message'     => '',
        ]);
    }

    /**
     * Simuler le dispatch sans l'enregistrer
     * Affiche une prévisualisation des attributions
     */
    public static function simuler()
    {
        $simulation = DispatchModel::simulerDispatchPreview();
        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches'  => $dispatches,
            'simulation'  => $simulation,
            'message'     => count($simulation) > 0 
                ? count($simulation) . ' attribution(s) possible(s). Cliquez sur "Valider" pour confirmer.'
                : 'Aucune nouvelle attribution possible.',
        ]);
    }

    /**
     * Valider et enregistrer réellement le dispatch
     */
    public static function valider()
    {
        $nb = DispatchModel::executerDispatch();

        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches'  => $dispatches,
            'simulation'  => null,
            'message'     => $nb > 0
                ? "✓ $nb attribution(s) enregistrée(s) avec succès !"
                : "Aucune nouvelle attribution effectuée.",
        ]);
    }
}
