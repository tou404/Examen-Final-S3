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
            'mode'        => 'ordre',
        ]);
    }

    /**
     * Simuler le dispatch sans l'enregistrer
     * Affiche une prévisualisation des attributions
     */
    public static function simuler()
    {
        $mode = isset($_GET['mode']) ? $_GET['mode'] : 'ordre';
        $allowed = ['ordre', 'min_need', 'proportionnel'];
        if (!in_array($mode, $allowed)) $mode = 'ordre';
        $simulation = DispatchModel::simulerDispatchPreview($mode);
        $dispatches = DispatchModel::getAll();
        $simulation = is_array($simulation) ? $simulation : [];

        Flight::render('dispatch.php', [
            'dispatches'  => $dispatches,
            'simulation'  => $simulation,
            'message'     => count($simulation) > 0
                ? count($simulation) . ' attribution(s) possible(s) (mode: ' . $mode . '). Cliquez sur "Valider" pour confirmer.'
                : 'Aucune nouvelle attribution possible.',
            'mode'        => $mode,
        ]);
    }

    /**
     * Valider et enregistrer réellement le dispatch
     */
    public static function valider()
    {
        $mode = isset($_GET['mode']) ? $_GET['mode'] : 'ordre';
        $allowed = ['ordre', 'min_need', 'proportionnel'];
        if (!in_array($mode, $allowed)) $mode = 'ordre';
        $nb = DispatchModel::executerDispatch($mode);

        $dispatches = DispatchModel::getAll();

        Flight::render('dispatch.php', [
            'dispatches'  => $dispatches,
            'simulation'  => null,
            'message'     => $nb > 0
                ? "✓ $nb attribution(s) enregistrée(s) avec succès ! (mode: $mode)"
                : "Aucune nouvelle attribution effectuée.",
            'mode'        => $mode,
        ]);
    }

    public static function reset()
    {
        DispatchModel::resetDispatches();
        $dispatches = DispatchModel::getAll();
        Flight::render('dispatch.php', [
            'dispatches' => $dispatches,
            'simulation' => null,
            'message'    => '✓ Réinitialisation effectuée : dispatches supprimés et besoins restaurés.',
            'mode'       => 'ordre',
        ]);
    }
}
