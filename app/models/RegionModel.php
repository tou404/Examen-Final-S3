<?php

class RegionModel
{
    public static function getAll()
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT id, nom FROM region ORDER BY nom');
        return $stmt->fetchAll();
    }

    public static function create($nom)
    {
        $db = Flight::db();
        $stmt = $db->prepare('INSERT INTO region (nom) VALUES (:nom)');
        $stmt->execute(['nom' => $nom]);
        return $db->lastInsertId();
    }
}
