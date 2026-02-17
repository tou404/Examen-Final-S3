<?php

class DistributionModel
{
    /**
     * Créer une nouvelle distribution
     */
    public static function create($besoinId, $villeId, $quantiteDistribuee, $lieuDistribution, $beneficiaires, $observations)
    {
        $db = Flight::db();
        $sql = 'INSERT INTO distribution (besoin_id, ville_id, quantite_distribuee, lieu_distribution, beneficiaires, observations)
                VALUES (:besoin_id, :ville_id, :quantite_distribuee, :lieu_distribution, :beneficiaires, :observations)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'besoin_id'            => $besoinId,
            'ville_id'             => $villeId,
            'quantite_distribuee'  => $quantiteDistribuee,
            'lieu_distribution'    => $lieuDistribution,
            'beneficiaires'        => $beneficiaires,
            'observations'         => $observations,
        ]);
        return $db->lastInsertId();
    }

    /**
     * Récupérer toutes les distributions avec détails
     */
    public static function getAll()
    {
        $db = Flight::db();
        $sql = 'SELECT d.id,
                       d.quantite_distribuee,
                       d.lieu_distribution,
                       d.beneficiaires,
                       d.observations,
                       d.date_distribution,
                       b.description AS besoin_description,
                       tb.libelle AS type_besoin,
                       v.nom AS ville,
                       r.nom AS region
                FROM distribution d
                JOIN besoin b ON d.besoin_id = b.id
                JOIN villes v ON d.ville_id = v.id
                JOIN region r ON v.region_id = r.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                ORDER BY d.date_distribution DESC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les besoins qui ont des quantités dispatchées/achetées disponibles pour distribution
     * (quantité dispatchée + achetée - déjà distribuée)
     */
    public static function getBesoinsDisponibles()
    {
        $db = Flight::db();
        $sql = 'SELECT b.id,
                       b.description,
                       b.prix_unitaire,
                       b.quantite,
                       b.ville_id,
                       v.nom AS ville,
                       tb.libelle AS type_besoin,
                       COALESCE(disp.total_dispatche, 0) AS quantite_dispatchee,
                       COALESCE(ach.total_achete, 0) AS quantite_achetee,
                       COALESCE(dist.total_distribue, 0) AS quantite_deja_distribuee,
                       (COALESCE(disp.total_dispatche, 0) + COALESCE(ach.total_achete, 0) - COALESCE(dist.total_distribue, 0)) AS quantite_disponible
                FROM besoin b
                JOIN villes v ON b.ville_id = v.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_attribuee) AS total_dispatche
                    FROM dispatch
                    GROUP BY besoin_id
                ) disp ON disp.besoin_id = b.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_achetee) AS total_achete
                    FROM achats
                    GROUP BY besoin_id
                ) ach ON ach.besoin_id = b.id
                LEFT JOIN (
                    SELECT besoin_id, SUM(quantite_distribuee) AS total_distribue
                    FROM distribution
                    GROUP BY besoin_id
                ) dist ON dist.besoin_id = b.id
                HAVING quantite_disponible > 0
                ORDER BY v.nom, b.description';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Stats globales de distribution
     */
    public static function getStats()
    {
        $db = Flight::db();

        // Total distribué
        $sql1 = 'SELECT COUNT(*) AS total_distributions,
                        COALESCE(SUM(quantite_distribuee), 0) AS total_quantite,
                        COALESCE(SUM(beneficiaires), 0) AS total_beneficiaires
                 FROM distribution';
        $stats = $db->query($sql1)->fetch();

        // Par ville
        $sql2 = 'SELECT v.nom AS ville,
                        COUNT(d.id) AS nb_distributions,
                        COALESCE(SUM(d.quantite_distribuee), 0) AS quantite_distribuee,
                        COALESCE(SUM(d.beneficiaires), 0) AS total_beneficiaires
                 FROM distribution d
                 JOIN villes v ON d.ville_id = v.id
                 GROUP BY v.id
                 ORDER BY quantite_distribuee DESC';
        $stats['par_ville'] = $db->query($sql2)->fetchAll();

        return $stats;
    }

    /**
     * Supprimer une distribution
     */
    public static function delete($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM distribution WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
