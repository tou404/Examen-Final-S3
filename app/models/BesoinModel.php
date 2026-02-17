<?php

class BesoinModel
{
    public static function create($villeId, $typeBesoinId, $description, $prixUnitaire, $quantite, $ordre = null)
    {
        $db = Flight::db();
        // Si pas d'ordre fourni, prendre le max + 1
        if ($ordre === null) {
            $stmt = $db->query('SELECT COALESCE(MAX(ordre), 0) + 1 AS next_ordre FROM besoin');
            $ordre = $stmt->fetch()['next_ordre'];
        }
        $sql = 'INSERT INTO besoin (ville_id, type_besoin_id, description, prix_unitaire, quantite, quantite_restante, ordre)
                VALUES (:ville_id, :type_besoin_id, :description, :prix_unitaire, :quantite, :quantite_restante, :ordre)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'ville_id'          => $villeId,
            'type_besoin_id'    => $typeBesoinId,
            'description'       => $description,
            'prix_unitaire'     => $prixUnitaire,
            'quantite'          => $quantite,
            'quantite_restante' => $quantite,
            'ordre'             => $ordre,
        ]);
        return $db->lastInsertId();
    }

    public static function getAllWithVilleAndType()
    {
        $db = Flight::db();
        $sql = 'SELECT b.id,
                       b.ville_id,
                       b.type_besoin_id,
                       v.nom AS ville,
                       tb.libelle AS type,
                       b.description,
                       b.prix_unitaire,
                       b.quantite,
                       b.quantite_restante,
                       b.ordre,
                       b.date_creation
                FROM besoin b
                JOIN villes v ON b.ville_id = v.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                ORDER BY b.ordre ASC';
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

    public static function update($id, $villeId, $typeBesoinId, $description, $prixUnitaire, $quantite)
    {
        $db = Flight::db();
        // Récupérer l'ancien besoin pour ajuster la quantité restante
        $ancien = self::getById($id);
        $diff = $quantite - $ancien['quantite'];
        $nouvelleQteRestante = max(0, $ancien['quantite_restante'] + $diff);

        $sql = 'UPDATE besoin SET ville_id = :ville_id, type_besoin_id = :type_besoin_id, 
                description = :description, prix_unitaire = :prix_unitaire, 
                quantite = :quantite, quantite_restante = :quantite_restante WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id'               => $id,
            'ville_id'         => $villeId,
            'type_besoin_id'   => $typeBesoinId,
            'description'      => $description,
            'prix_unitaire'    => $prixUnitaire,
            'quantite'         => $quantite,
            'quantite_restante'=> $nouvelleQteRestante,
        ]);
    }

    public static function delete($id)
    {
        $db = Flight::db();
        $stmt = $db->prepare('DELETE FROM besoin WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
