<?php

class VilleModel
{
    public static function getAllWithRegion()
    {
        $db = Flight::db();
        $sql = 'SELECT v.id, v.nom AS ville, r.nom AS region, v.region_id
                FROM villes v
                JOIN region r ON v.region_id = r.id
                ORDER BY r.nom, v.nom';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function getAll()
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT id, nom FROM villes ORDER BY nom');
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

    public static function update($id, $regionId, $nom)
    {
        $db = Flight::db();
        $sql = 'UPDATE villes SET region_id = :region_id, nom = :nom WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id'        => $id,
            'region_id' => $regionId,
            'nom'       => $nom,
        ]);
    }

    public static function delete($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM villes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
