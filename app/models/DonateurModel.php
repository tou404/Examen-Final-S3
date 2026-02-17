<?php

class DonateurModel
{
    public static function getAll()
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT id, nom, prenom, email, telephone FROM donateurs ORDER BY nom, prenom');
        return $stmt->fetchAll();
    }

    public static function create($nom, $prenom)
    {
        $db = Flight::db();
        $sql = 'INSERT INTO donateurs (nom, prenom) VALUES (:nom, :prenom)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom'    => $nom,
            'prenom' => $prenom,
        ]);
        return $db->lastInsertId();
    }

    public static function findByName($nom, $prenom)
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM donateurs WHERE nom = :nom AND prenom = :prenom');
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
        return $stmt->fetch();
    }
}
