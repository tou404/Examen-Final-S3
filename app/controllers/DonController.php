<?php

class DonController
{
    public static function index()
    {
        $types = class_exists('TypeBesoinModel') ? TypeBesoinModel::getAll() : [];
        $dons  = class_exists('DonModel') ? DonModel::getAllWithType() : [];

        Flight::render('don.php', [
            'types' => $types,
            'dons'  => $dons,
        ]);
    }

    public static function store()
    {
        $data = Flight::request()->data;

        $typeBesoinId  = $data->type_besoin_id;
        $designation   = trim($data->designation);
        $quantite      = (float) $data->quantite_donnee;
        $dateSaisie    = $data->date_saisie ?: date('Y-m-d');

        if ($typeBesoinId && $designation !== '' && $quantite >= 0) {
            DonModel::create($typeBesoinId, $designation, $quantite, $dateSaisie);
        }

        Flight::redirect('/don');
    }
}
