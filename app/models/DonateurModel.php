<?php

class DonateurModel
{
    public static function getAll()
    {
        $db = Flight::db();
        $stmt = $db->query('SELECT id, nom, prenom, email, telephone FROM donateurs ORDER BY nom, prenom');
        return $stmt->fetchAll();
    }

    public static function create($nom, $prenom, $email, $telephone)
    {
        $db = Flight::db();
        $sql = 'INSERT INTO donateurs (nom, prenom, email, telephone) VALUES (:nom, :prenom, :email, :telephone)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom'       => $nom,
            'prenom'    => $prenom,
            'email'     => $email,
            'telephone' => $telephone,
        ]);
        return $db->lastInsertId();
    }

    public static function findByEmail($email)
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM donateurs WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
