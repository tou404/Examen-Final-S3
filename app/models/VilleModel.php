<?php

class VilleModel
{
    public static function getAllWithRegion()
    {
        $db = Flight::db();

        $sql = 'SELECT v.id, v.nom AS ville, r.nom AS region
                FROM villes v
                JOIN regions r ON v.region_id = r.id
                ORDER BY r.nom, v.nom';

        $stmt = $db->query($sql);

        return $stmt->fetchAll();
    }

    public static function create($regionId, $nom)
    {
        $db = Flight::db();

        $sql = 'INSERT INTO villes (region_id, nom) VALUES (:region_id, :nom)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'region_id' => $regionId,
            'nom'       => $nom,
        ]);

        return $db->lastInsertId();
    }
}
