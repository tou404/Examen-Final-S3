<?php

class BesoinModel
{
    public static function create($villeId, $typeBesoinId, $description, $prixUnitaire, $quantite)
    {
        $db = Flight::db();
        $sql = 'INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante)
                VALUES (:ville_id, :type_besoin_id, :description, :prix_unitaire, :quantite, :quantite_restante)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'ville_id'          => $villeId,
            'type_besoin_id'    => $typeBesoinId,
            'description'       => $description,
            'prix_unitaire'     => $prixUnitaire,
            'quantite'          => $quantite,
            'quantite_restante' => $quantite,
        ]);
        return $db->lastInsertId();
    }

    public static function getAllWithVilleAndType()
    {
        $db = Flight::db();
        $sql = 'SELECT b.id,
                       v.nom AS ville,
                       tb.libelle AS type,
                       b.description,
                       b.prix_unitaire,
                       b.quantite,
                       b.quantite_restante,
                       b.date_creation
                FROM besoin b
                JOIN villes v ON b.ville_id = v.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                ORDER BY b.date_creation DESC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('SELECT * FROM besoin WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function updateQuantiteRestante($id, $quantiteRestante)
    {
        $db = Flight::db();
        $stmt = $db->prepare('UPDATE besoin SET quantite_restante = :qr WHERE id = :id');
        $stmt->execute(['qr' => $quantiteRestante, 'id' => $id]);
    }
}
