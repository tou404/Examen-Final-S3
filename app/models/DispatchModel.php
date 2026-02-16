<?php

class DispatchModel
{
    public static function getSituationParVille()
    {
        $db = Flight::db();

        $sql = 'SELECT v.id AS ville_id,
                       v.nom AS ville,
                       r.nom AS region,
                       COALESCE(SUM(b.quantite_demande * b.prix_unitaire), 0) AS valeur_besoins,
                       COALESCE(SUM(a.quantite_attribuee * b.prix_unitaire), 0) AS valeur_attribuee
                FROM villes v
                JOIN regions r ON v.region_id = r.id
                LEFT JOIN besoins b ON b.ville_id = v.id
                LEFT JOIN attributions a ON a.besoin_id = b.id
                GROUP BY v.id, v.nom, r.nom
                ORDER BY r.nom, v.nom';

        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['reste_a_couvrir'] = $row['valeur_besoins'] - $row['valeur_attribuee'];
        }

        return $rows;
    }
}
