<?php

class BesoinController
{
    public static function index()
    {
        $villes  = VilleModel::getAllWithRegion();
        $types   = TypeBesoinModel::getAll();
        $besoins = BesoinModel::getAllWithVilleAndType();

        Flight::render('besoin.php', [
            'villes'  => $villes,
            'types'   => $types,
            'besoins' => $besoins,
        ]);
    }

    public static function store()
    {
        $data = Flight::request()->data;

        $villeId      = $data->ville_id;
        $typeBesoinId = $data->type_besoin_id;
        $description  = trim($data->description);
        $prixUnitaire = (float) $data->prix_unitaire;
        $quantite     = (int) $data->quantite;

        if ($villeId && $typeBesoinId && $description !== '' && $prixUnitaire >= 0 && $quantite > 0) {
            BesoinModel::create($villeId, $typeBesoinId, $description, $prixUnitaire, $quantite);
        }

        Flight::redirect('/besoin');
    }

    public static function update()
    {
        $data = Flight::request()->data;

        $id           = (int) $data->id;
        $villeId      = (int) $data->ville_id;
        $typeBesoinId = (int) $data->type_besoin_id;
        $description  = trim($data->description);
        $prixUnitaire = (float) $data->prix_unitaire;
        $quantite     = (int) $data->quantite;

        if ($id && $villeId && $typeBesoinId && $description !== '' && $prixUnitaire >= 0 && $quantite > 0) {
            BesoinModel::update($id, $villeId, $typeBesoinId, $description, $prixUnitaire, $quantite);
        }

        Flight::redirect('/besoin');
    }

    public static function delete($id)
    {
        if ($id) {
            BesoinModel::delete((int) $id);
        }
        Flight::redirect('/besoin');
    }
}
