<?php

class DonModel
{
    public static function create($donateurId, $typeBesoinId, $designation, $quantite, $montant)
    {
        $db = Flight::db();//Récupère la connexion DB
        $sql = 'INSERT INTO dons (donateur_id, type_besoin_id, designation, quantite, montant)
                VALUES (:donateur_id, :type_besoin_id, :designation, :quantite, :montant)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'donateur_id'    => $donateurId,
            'type_besoin_id' => $typeBesoinId,
            'designation'    => $designation,
            'quantite'       => $quantite,
            'montant'        => $montant,
        ]);
        return $db->lastInsertId();
    }

    public static function getAllWithType()   //Liste complète des dons
    {
        $db = Flight::db();
        $sql = 'SELECT d.id,
                       tb.libelle AS type,
                       d.designation,
                       d.quantite,
                       d.montant,
                       d.date_don,
                       CONCAT(do2.prenom, " ", do2.nom) AS donateur
                FROM dons d
                JOIN type_besoin tb ON d.type_besoin_id = tb.id
                LEFT JOIN donateurs do2 ON d.donateur_id = do2.id
                ORDER BY d.date_don DESC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function getNonDispatched() //Dons non encore totalement distribués
    {
        $db = Flight::db();
        $sql = 'SELECT d.id, d.type_besoin_id, d.designation, d.quantite, d.montant, d.date_don,
                       COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante,
                       COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) AS mnt_restant
                FROM dons d
                LEFT JOIN dispatch di ON di.don_id = d.id
                GROUP BY d.id
                HAVING qte_restante > 0 OR mnt_restant > 0
                ORDER BY d.date_don ASC, d.id ASC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM dons WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $donateurId, $typeBesoinId, $designation, $quantite, $montant)
    {
        $db = Flight::db();
        $sql = 'UPDATE dons SET donateur_id = :donateur_id, type_besoin_id = :type_besoin_id, 
                designation = :designation, quantite = :quantite, montant = :montant WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id'             => $id,
            'donateur_id'    => $donateurId,
            'type_besoin_id' => $typeBesoinId,
            'designation'    => $designation,
            'quantite'       => $quantite,
            'montant'        => $montant,
        ]);
    }

    public static function delete($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM dons WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
