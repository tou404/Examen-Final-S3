<?php

class AchatModel
{
    /**
     * Créer un nouvel achat
     */
    public static function create($besoinId, $donId, $quantiteAchetee, $prixUnitaire, $fraisPourcentage)
    {
        $db = Flight::db();
        
        $montantHt = $quantiteAchetee * $prixUnitaire;
        $montantFrais = $montantHt * ($fraisPourcentage / 100);
        $montantTotal = $montantHt + $montantFrais;

        $sql = 'INSERT INTO achats (besoin_id, don_id, quantite_achetee, prix_unitaire, montant_ht, frais_pourcentage, montant_frais, montant_total)
                VALUES (:besoin_id, :don_id, :quantite_achetee, :prix_unitaire, :montant_ht, :frais_pourcentage, :montant_frais, :montant_total)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'besoin_id'         => $besoinId,
            'don_id'            => $donId,
            'quantite_achetee'  => $quantiteAchetee,
            'prix_unitaire'     => $prixUnitaire,
            'montant_ht'        => $montantHt,
            'frais_pourcentage' => $fraisPourcentage,
            'montant_frais'     => $montantFrais,
            'montant_total'     => $montantTotal,
        ]);
        return $db->lastInsertId();
    }

    /**
     * Récupérer tous les achats avec détails
     */
    public static function getAll($villeId = null)
    {
        $db = Flight::db();
        $sql = 'SELECT a.id,
                       b.description AS besoin_description,
                       v.nom AS ville,
                       v.id AS ville_id,
                       tb.libelle AS type_besoin,
                       a.quantite_achetee,
                       a.prix_unitaire,
                       a.montant_ht,
                       a.frais_pourcentage,
                       a.montant_frais,
                       a.montant_total,
                       a.date_achat,
                       CONCAT(do2.prenom, " ", do2.nom) AS donateur
                FROM achats a
                JOIN besoin b ON a.besoin_id = b.id
                JOIN villes v ON b.ville_id = v.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                JOIN dons d ON a.don_id = d.id
                LEFT JOIN donateurs do2 ON d.donateur_id = do2.id';
        
        if ($villeId) {
            $sql .= ' WHERE v.id = :ville_id';
        }
        $sql .= ' ORDER BY a.date_achat DESC';

        $stmt = $db->prepare($sql);
        if ($villeId) {
            $stmt->execute(['ville_id' => $villeId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    /**
     * Vérifier si un besoin a déjà un don en nature/matériel non dispatché correspondant
     */
    public static function besoinExisteDansDonsRestants($besoinId)
    {
        $db = Flight::db();
        
        // Récupérer le type et la description du besoin
        $besoin = BesoinModel::getById($besoinId);
        if (!$besoin) return false;
        
        // Chercher si un don du même type existe avec quantité restante > 0
        // On vérifie les dons de type Nature (1) ou Matériels (2)
        $sql = 'SELECT d.id, d.designation,
                       COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante
                FROM dons d
                LEFT JOIN dispatch di ON di.don_id = d.id
                WHERE d.type_besoin_id = :type_id
                AND d.type_besoin_id IN (1, 2)
                GROUP BY d.id
                HAVING qte_restante > 0';
        $stmt = $db->prepare($sql);
        $stmt->execute(['type_id' => $besoin['type_besoin_id']]);
        $donsRestants = $stmt->fetchAll();

        return count($donsRestants) > 0;
    }

    /**
     * Récupérer les dons en argent avec montant restant disponible
     */
    public static function getDonsArgentDisponibles()
    {
        $db = Flight::db();
        $sql = 'SELECT d.id, d.designation, d.montant,
                       CONCAT(do2.prenom, " ", do2.nom) AS donateur,
                       COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) - COALESCE(achats_total.total_utilise, 0) AS montant_restant
                FROM dons d
                LEFT JOIN dispatch di ON di.don_id = d.id
                LEFT JOIN donateurs do2 ON d.donateur_id = do2.id
                LEFT JOIN (
                    SELECT don_id, SUM(montant_total) AS total_utilise
                    FROM achats
                    GROUP BY don_id
                ) achats_total ON achats_total.don_id = d.id
                WHERE d.type_besoin_id = 3
                GROUP BY d.id
                HAVING montant_restant > 0
                ORDER BY d.date_don ASC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les besoins restants (nature et matériels uniquement)
     */
    public static function getBesoinsRestants($villeId = null)
    {
        $db = Flight::db();
        $sql = 'SELECT b.id, b.description, b.prix_unitaire, b.quantite_restante,
                       v.nom AS ville, v.id AS ville_id,
                       tb.libelle AS type_besoin, tb.code AS type_code
                FROM besoin b
                JOIN villes v ON b.ville_id = v.id
                JOIN type_besoin tb ON b.type_besoin_id = tb.id
                WHERE b.quantite_restante > 0
                AND b.type_besoin_id IN (1, 2)';
        
        if ($villeId) {
            $sql .= ' AND v.id = :ville_id';
        }
        $sql .= ' ORDER BY b.date_creation ASC';

        $stmt = $db->prepare($sql);
        if ($villeId) {
            $stmt->execute(['ville_id' => $villeId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    /**
     * Calculer le total des achats
     */
    public static function getTotalAchats()
    {
        $db = Flight::db();
        $sql = 'SELECT SUM(montant_total) AS total FROM achats';
        $stmt = $db->query($sql);
        $row = $stmt->fetch();
        return $row ? (float) $row['total'] : 0;
    }
}
