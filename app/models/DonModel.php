<?php

class DonModel
{
    public static function create($typeBesoinId, $designation, $quantiteDonnee, $dateSaisie)
    {
        $db = Flight::db();

        $sql = 'INSERT INTO dons (type_besoin_id, designation, quantite_donnee, date_saisie, ordre_saisie)
                VALUES (:type_besoin_id, :designation, :quantite_donnee, :date_saisie, :ordre_saisie)';

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'type_besoin_id'  => $typeBesoinId,
            'designation'     => $designation,
            'quantite_donnee' => $quantiteDonnee,
            'date_saisie'     => $dateSaisie,
            // ordre_saisie simple : timestamp courant
            'ordre_saisie'    => time(),
        ]);

        return $db->lastInsertId();
    }

    public static function getAllWithType()
    {
        $db = Flight::db();

        $sql = 'SELECT d.id,
                       t.libelle AS type,
                       d.designation,
                       d.quantite_donnee,
                       d.date_saisie
                FROM dons d
                JOIN types_besoin t ON d.type_besoin_id = t.id
                ORDER BY d.date_saisie DESC, d.ordre_saisie DESC';

        $stmt = $db->query($sql);

        return $stmt->fetchAll();
    }
}
