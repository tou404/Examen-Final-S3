<?php

class TypeBesoinModel
{
    public static function getAll()
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT id, code, libelle FROM type_besoin ORDER BY id');
        return $stmt->fetchAll();
    }
}
