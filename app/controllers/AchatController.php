<?php

class AchatController
{
    /**
     * Afficher la page des achats
     */
    public static function index()
    {
        $villeId = Flight::request()->query->ville_id ?? null;
        
        $achats = AchatModel::getAll($villeId);
        $besoinsRestants = AchatModel::getBesoinsRestants($villeId);
        $donsArgent = AchatModel::getDonsArgentDisponibles();
        $villes = VilleModel::getAll();
        $fraisAchat = ConfigModel::getFraisAchat();

        Flight::render('achat.php', [
            'achats'          => $achats,
            'besoinsRestants' => $besoinsRestants,
            'donsArgent'      => $donsArgent,
            'villes'          => $villes,
            'fraisAchat'      => $fraisAchat,
            'villeIdFiltre'   => $villeId,
            'message'         => '',
            'error'           => '',
        ]);
    }

    /**
     * Effectuer un achat
     */
    public static function store()
    {
        $data = Flight::request()->data;
        
        $besoinId = $data->besoin_id ?? null;
        $donId = $data->don_id ?? null;
        $quantite = (int) ($data->quantite ?? 0);

        // Validation
        if (!$besoinId || !$donId || $quantite <= 0) {
            return self::renderWithError('Veuillez remplir tous les champs correctement.');
        }

        // Récupérer le besoin
        $besoin = BesoinModel::getById($besoinId);
        if (!$besoin) {
            return self::renderWithError('Besoin introuvable.');
        }

        // Vérifier si un don en nature/matériel existe encore pour ce type
        if (AchatModel::besoinExisteDansDonsRestants($besoinId)) {
            return self::renderWithError(
                'Erreur : Il existe encore des dons en nature ou matériels disponibles pour ce type de besoin. ' .
                'Veuillez d\'abord dispatcher ces dons avant d\'effectuer un achat.'
            );
        }

        // Vérifier la quantité restante du besoin
        if ($quantite > $besoin['quantite_restante']) {
            return self::renderWithError('La quantité demandée dépasse la quantité restante du besoin.');
        }

        // Récupérer le don en argent et vérifier le montant disponible
        $donsArgent = AchatModel::getDonsArgentDisponibles();
        $donTrouve = null;
        foreach ($donsArgent as $d) {
            if ($d['id'] == $donId) {
                $donTrouve = $d;
                break;
            }
        }

        if (!$donTrouve) {
            return self::renderWithError('Don en argent introuvable ou déjà épuisé.');
        }

        // Calculer le coût total avec frais
        $frais = ConfigModel::getFraisAchat();
        $montantHt = $quantite * $besoin['prix_unitaire'];
        $montantTotal = $montantHt * (1 + $frais / 100);

        if ($montantTotal > $donTrouve['montant_restant']) {
            return self::renderWithError(
                'Le montant total de l\'achat (' . number_format($montantTotal, 0, ',', ' ') . ' Ar) ' .
                'dépasse le montant restant du don (' . number_format($donTrouve['montant_restant'], 0, ',', ' ') . ' Ar).'
            );
        }

        // Effectuer l'achat
        AchatModel::create($besoinId, $donId, $quantite, $besoin['prix_unitaire'], $frais);

        // Mettre à jour la quantité restante du besoin
        BesoinModel::updateQuantiteRestante($besoinId, $besoin['quantite_restante'] - $quantite);

        // Rediriger avec message de succès
        $villeId = Flight::request()->query->ville_id ?? null;
        $achats = AchatModel::getAll($villeId);
        $besoinsRestants = AchatModel::getBesoinsRestants($villeId);
        $donsArgent = AchatModel::getDonsArgentDisponibles();
        $villes = VilleModel::getAll();

        Flight::render('achat.php', [
            'achats'          => $achats,
            'besoinsRestants' => $besoinsRestants,
            'donsArgent'      => $donsArgent,
            'villes'          => $villes,
            'fraisAchat'      => $frais,
            'villeIdFiltre'   => $villeId,
            'message'         => 'Achat effectué avec succès ! ' . $quantite . ' unité(s) achetée(s) pour ' . number_format($montantTotal, 0, ',', ' ') . ' Ar.',
            'error'           => '',
        ]);
    }

    /**
     * Mettre à jour le frais d'achat
     */
    public static function updateFrais()
    {
        $data = Flight::request()->data;
        $frais = (float) ($data->frais_achat ?? 10);

        if ($frais < 0 || $frais > 100) {
            return self::renderWithError('Le pourcentage de frais doit être entre 0 et 100.');
        }

        ConfigModel::setFraisAchat($frais);

        Flight::redirect('/achat?message=frais_updated');
    }

    /**
     * Helper pour afficher avec erreur
     */
    private static function renderWithError($error)
    {
        $villeId = Flight::request()->query->ville_id ?? null;
        
        Flight::render('achat.php', [
            'achats'          => AchatModel::getAll($villeId),
            'besoinsRestants' => AchatModel::getBesoinsRestants($villeId),
            'donsArgent'      => AchatModel::getDonsArgentDisponibles(),
            'villes'          => VilleModel::getAll(),
            'fraisAchat'      => ConfigModel::getFraisAchat(),
            'villeIdFiltre'   => $villeId,
            'message'         => '',
            'error'           => $error,
        ]);
    }
}
