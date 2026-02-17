<?php

class RecapController
{
    /**
     * Afficher la page de récapitulation
     */
    public static function index()
    {
        $stats = self::getStats();
        
        Flight::render('recapitulation.php', [
            'stats' => $stats,
        ]);
    }

    /**
     * API JSON pour actualisation Ajax
     */
    public static function api()
    {
        $stats = self::getStats();
        
        Flight::json($stats);
    }

    /**
     * Calculer toutes les statistiques
     */
    private static function getStats()
    {
        $db = Flight::db();

        // Besoins totaux (en montant)
        $sqlBesoinsTotaux = 'SELECT 
            SUM(quantite * prix_unitaire) AS montant_total,
            SUM(quantite) AS quantite_totale,
            COUNT(*) AS nombre_besoins
            FROM besoin';
        $besoinsTotaux = $db->query($sqlBesoinsTotaux)->fetch();

        // Besoins satisfaits via dispatch (dons en nature/matériels)
        $sqlDispatch = 'SELECT 
            COALESCE(SUM(di.quantite_attribuee * b.prix_unitaire), 0) AS montant_dispatch,
            COALESCE(SUM(di.quantite_attribuee), 0) AS quantite_dispatch
            FROM dispatch di
            JOIN besoin b ON di.besoin_id = b.id';
        $dispatch = $db->query($sqlDispatch)->fetch();

        // Besoins satisfaits via achats
        $sqlAchats = 'SELECT 
            COALESCE(SUM(a.montant_ht), 0) AS montant_achats_ht,
            COALESCE(SUM(a.montant_total), 0) AS montant_achats_total,
            COALESCE(SUM(a.quantite_achetee), 0) AS quantite_achats
            FROM achats a';
        $achats = $db->query($sqlAchats)->fetch();

        // Montant total satisfait (dispatch + achats)
        $montantSatisfait = (float) $dispatch['montant_dispatch'] + (float) $achats['montant_achats_ht'];
        $quantiteSatisfaite = (int) $dispatch['quantite_dispatch'] + (int) $achats['quantite_achats'];

        // Besoins restants
        $sqlRestants = 'SELECT 
            COALESCE(SUM(quantite_restante * prix_unitaire), 0) AS montant_restant,
            COALESCE(SUM(quantite_restante), 0) AS quantite_restante
            FROM besoin
            WHERE quantite_restante > 0';
        $restants = $db->query($sqlRestants)->fetch();

        // Dons totaux reçus
        $sqlDonsTotaux = 'SELECT 
            COALESCE(SUM(CASE WHEN type_besoin_id IN (1,2) THEN quantite ELSE 0 END), 0) AS total_qte_nature,
            COALESCE(SUM(CASE WHEN type_besoin_id = 3 THEN montant ELSE 0 END), 0) AS total_argent
            FROM dons';
        $donsTotaux = $db->query($sqlDonsTotaux)->fetch();

        // Dons en argent restants
        $sqlArgentRestant = 'SELECT 
            COALESCE(SUM(d.montant), 0) - 
            COALESCE((SELECT SUM(montant_attribue) FROM dispatch WHERE don_id IN (SELECT id FROM dons WHERE type_besoin_id = 3)), 0) -
            COALESCE((SELECT SUM(montant_total) FROM achats), 0) AS argent_restant
            FROM dons d
            WHERE d.type_besoin_id = 3';
        $argentRestant = $db->query($sqlArgentRestant)->fetch();

        // Statistiques par ville
        $sqlParVille = 'SELECT 
            v.nom AS ville,
            r.nom AS region,
            SUM(b.quantite * b.prix_unitaire) AS besoins_totaux,
            SUM(b.quantite_restante * b.prix_unitaire) AS besoins_restants,
            SUM(b.quantite * b.prix_unitaire) - SUM(b.quantite_restante * b.prix_unitaire) AS besoins_satisfaits
            FROM besoin b
            JOIN villes v ON b.ville_id = v.id
            JOIN region r ON v.region_id = r.id
            GROUP BY v.id
            ORDER BY besoins_restants DESC';
        $parVille = $db->query($sqlParVille)->fetchAll();

        // Pourcentage de couverture
        $montantTotal = (float) $besoinsTotaux['montant_total'];
        $pourcentage = $montantTotal > 0 ? round(($montantSatisfait / $montantTotal) * 100, 1) : 0;

        return [
            'besoins_totaux' => [
                'montant' => (float) $besoinsTotaux['montant_total'],
                'quantite' => (int) $besoinsTotaux['quantite_totale'],
                'nombre' => (int) $besoinsTotaux['nombre_besoins'],
            ],
            'besoins_satisfaits' => [
                'montant' => $montantSatisfait,
                'quantite' => $quantiteSatisfaite,
                'via_dispatch' => (float) $dispatch['montant_dispatch'],
                'via_achats' => (float) $achats['montant_achats_ht'],
            ],
            'besoins_restants' => [
                'montant' => (float) $restants['montant_restant'],
                'quantite' => (int) $restants['quantite_restante'],
            ],
            'dons' => [
                'nature_materiel_qte' => (int) $donsTotaux['total_qte_nature'],
                'argent_total' => (float) $donsTotaux['total_argent'],
                'argent_restant' => (float) $argentRestant['argent_restant'],
            ],
            'achats' => [
                'montant_ht' => (float) $achats['montant_achats_ht'],
                'montant_total' => (float) $achats['montant_achats_total'],
                'quantite' => (int) $achats['quantite_achats'],
            ],
            'pourcentage_couverture' => $pourcentage,
            'par_ville' => $parVille,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}
