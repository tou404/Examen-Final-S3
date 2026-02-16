<?php

class VilleController
{
    public static function index()
    {
        $regions = RegionModel::getAll();
        $villes  = VilleModel::getAllWithRegion();

        Flight::render('ville.php', [
            'regions' => $regions,
            'villes'  => $villes,
        ]);
    }

    public static function store()
    {
        $regionId = Flight::request()->data->region_id;
        $nom      = trim(Flight::request()->data->nom);

        if ($regionId && $nom !== '') {
            VilleModel::create($regionId, $nom);
        }

        Flight::redirect('/ville');
    }
}
