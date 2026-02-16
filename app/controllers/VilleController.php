<?php

class VilleController
{
    public static function index()
    {
        $regions = class_exists('RegionModel') ? RegionModel::getAll() : [];
        $villes  = class_exists('VilleModel') ? VilleModel::getAllWithRegion() : [];

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
