<?php

class DistributionController
{
    public static function index()
    {
        $distributions = DistributionModel::getAll();
        $besoinsDisponibles = DistributionModel::getBesoinsDisponibles();
        $stats = DistributionModel::getStats();
        $villes = VilleModel::getAll();

        Flight::render('distribution.php', [
            'distributions'      => $distributions,
            'besoinsDisponibles' => $besoinsDisponibles,
            'stats'              => $stats,
            'villes'             => $villes,
            'message'            => '',
            'error'              => '',
        ]);
    }

    public static function store()
    {
        $besoinId           = intval(Flight::request()->data->besoin_id ?? 0);
        $villeId            = intval(Flight::request()->data->ville_id ?? 0);
        $quantiteDistribuee = intval(Flight::request()->data->quantite_distribuee ?? 0);
        $lieuDistribution   = trim(Flight::request()->data->lieu_distribution ?? '');
        $beneficiaires      = intval(Flight::request()->data->beneficiaires ?? 0);
        $observations       = trim(Flight::request()->data->observations ?? '');

        // Validation
        if ($besoinId <= 0 || $villeId <= 0 || $quantiteDistribuee <= 0) {
            $distributions = DistributionModel::getAll();
            $besoinsDisponibles = DistributionModel::getBesoinsDisponibles();
            $stats = DistributionModel::getStats();
            $villes = VilleModel::getAll();

            Flight::render('distribution.php', [
                'distributions'      => $distributions,
                'besoinsDisponibles' => $besoinsDisponibles,
                'stats'              => $stats,
                'villes'             => $villes,
                'message'            => '',
                'error'              => 'Veuillez remplir tous les champs obligatoires.',
            ]);
            return;
        }

        // Vérifier la disponibilité
        $besoinsDisponibles = DistributionModel::getBesoinsDisponibles();
        $disponible = 0;
        foreach ($besoinsDisponibles as $b) {
            if ($b['id'] == $besoinId) {
                $disponible = intval($b['quantite_disponible']);
                break;
            }
        }

        if ($quantiteDistribuee > $disponible) {
            $distributions = DistributionModel::getAll();
            $stats = DistributionModel::getStats();
            $villes = VilleModel::getAll();

            Flight::render('distribution.php', [
                'distributions'      => $distributions,
                'besoinsDisponibles' => $besoinsDisponibles,
                'stats'              => $stats,
                'villes'             => $villes,
                'message'            => '',
                'error'              => "Quantité demandée ($quantiteDistribuee) supérieure à la quantité disponible ($disponible).",
            ]);
            return;
        }

        DistributionModel::create($besoinId, $villeId, $quantiteDistribuee, $lieuDistribution, $beneficiaires ?: null, $observations ?: null);

        $distributions = DistributionModel::getAll();
        $besoinsDisponibles = DistributionModel::getBesoinsDisponibles();
        $stats = DistributionModel::getStats();
        $villes = VilleModel::getAll();

        Flight::render('distribution.php', [
            'distributions'      => $distributions,
            'besoinsDisponibles' => $besoinsDisponibles,
            'stats'              => $stats,
            'villes'             => $villes,
            'message'            => "Distribution de $quantiteDistribuee unité(s) enregistrée avec succès !",
            'error'              => '',
        ]);
    }

    public static function delete($id)
    {
        DistributionModel::delete($id);

        $distributions = DistributionModel::getAll();
        $besoinsDisponibles = DistributionModel::getBesoinsDisponibles();
        $stats = DistributionModel::getStats();
        $villes = VilleModel::getAll();

        Flight::render('distribution.php', [
            'distributions'      => $distributions,
            'besoinsDisponibles' => $besoinsDisponibles,
            'stats'              => $stats,
            'villes'             => $villes,
            'message'            => 'Distribution supprimée.',
            'error'              => '',
        ]);
    }
}
