<?php

class BesoinController
{
    public static function index()
    {
        $villes    = class_exists('VilleModel') ? VilleModel::getAllWithRegion() : [];
        $types     = class_exists('TypeBesoinModel') ? TypeBesoinModel::getAll() : [];
        $besoins   = class_exists('BesoinModel') ? BesoinModel::getAllWithVilleAndType() : [];

        Flight::render('besoin.php', [
            'villes'  => $villes,
            'types'   => $types,
            'besoins' => $besoins,
        ]);
    }

    public static function store()
    {
        $data = Flight::request()->data;

        $villeId       = $data->ville_id;
        $typeBesoinId  = $data->type_besoin_id;
        $designation   = trim($data->designation);
        $prixUnitaire  = (float) $data->prix_unitaire;
        $quantite      = (float) $data->quantite_demande;

        if ($villeId && $typeBesoinId && $designation !== '' && $prixUnitaire >= 0 && $quantite >= 0) {
            BesoinModel::create($villeId, $typeBesoinId, $designation, $prixUnitaire, $quantite);
        }

        Flight::redirect('/besoin');
    }
}
