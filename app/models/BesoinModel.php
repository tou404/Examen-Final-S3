<?php

class BesoinModel
{
    public static function create($villeId, $typeBesoinId, $designation, $prixUnitaire, $quantiteDemande)
    {
        $db = Flight::db();

        $sql = 'INSERT INTO besoins (ville_id, type_besoin_id, designation, prix_unitaire, quantite_demande)
                VALUES (:ville_id, :type_besoin_id, :designation, :prix_unitaire, :quantite_demande)';

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'ville_id'         => $villeId,
            'type_besoin_id'   => $typeBesoinId,
            'designation'      => $designation,
            'prix_unitaire'    => $prixUnitaire,
            'quantite_demande' => $quantiteDemande,
        ]);

        return $db->lastInsertId();
    }

    public static function getAllWithVilleAndType()
    {
        $db = Flight::db();

        $sql = 'SELECT b.id,
                       v.nom AS ville,
                       t.libelle AS type,
                       b.designation,
                       b.prix_unitaire,
                       b.quantite_demande
                FROM besoins b
                JOIN villes v ON b.ville_id = v.id
                JOIN types_besoin t ON b.type_besoin_id = t.id
                ORDER BY v.nom, t.libelle, b.designation';

        $stmt = $db->query($sql);

        return $stmt->fetchAll();
    }
}
