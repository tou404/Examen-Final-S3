<?php

class DonController
{
    public static function index()
    {
        $types = TypeBesoinModel::getAll();
        $dons  = DonModel::getAllWithType();

        Flight::render('don.php', [
            'types' => $types,
            'dons'  => $dons,
        ]);
    }

    public static function store()
    {
        $data = Flight::request()->data;

        // Gérer le donateur (créer ou retrouver)
        $nom       = trim($data->nom);
        $prenom    = trim($data->prenom);
        $email     = trim($data->email);
        $telephone = trim($data->telephone ?? '');

        $donateur = DonateurModel::findByEmail($email);
        if (!$donateur) {
            $donateurId = DonateurModel::create($nom, $prenom, $email, $telephone);
        } else {
            $donateurId = $donateur['id'];
        }

        // Enregistrer le don
        $typeBesoinId = $data->type_besoin_id;
        $designation  = trim($data->designation ?? '');
        $quantite     = (int) ($data->quantite ?? 0);
        $montant      = (float) ($data->montant ?? 0);

        if ($typeBesoinId && ($quantite > 0 || $montant > 0)) {
            DonModel::create($donateurId, $typeBesoinId, $designation, $quantite, $montant);
        }

        Flight::redirect('/don');
    }
}
