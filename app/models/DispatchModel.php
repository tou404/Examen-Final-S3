<?php

class DispatchModel
{
    public static function dispatch($donId, $besoinId, $quantiteAttribuee, $montantAttribue)
    {
        $db = Flight::db();
        $sql = 'INSERT INTO dispatch (don_id, besoin_id, quantite_attribuee, montant_attribue)
                VALUES (:don_id, :besoin_id, :quantite_attribuee, :montant_attribue)';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'don_id'             => $donId,
            'besoin_id'          => $besoinId,
            'quantite_attribuee' => $quantiteAttribuee,
            'montant_attribue'   => $montantAttribue,
        ]);
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $db = Flight::db();
        $sql = 'SELECT di.id,
                       d.designation AS don_designation,
                       b.description AS besoin_description,
                       v.nom AS ville,
                       di.quantite_attribuee,
                       di.montant_attribue,
                       di.date_dispatch
                FROM dispatch di
                JOIN dons d ON di.don_id = d.id
                JOIN besoin b ON di.besoin_id = b.id
                JOIN villes v ON b.ville_id = v.id
                ORDER BY di.date_dispatch DESC';
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    public static function getSituationParVille()
    {
        $db = Flight::db();
        $sql = 'SELECT v.id AS ville_id,
                       v.nom AS ville,
                       r.nom AS region,
                       COALESCE(besoins_agg.total_valeur, 0) AS valeur_besoins,
                       COALESCE(besoins_agg.total_qte, 0) AS total_besoins_qte,
                       COALESCE(dispatch_agg.total_attribue, 0) AS valeur_attribuee,
                       COALESCE(dispatch_agg.total_qte_attribuee, 0) AS total_attribuee_qte
                FROM villes v
                JOIN region r ON v.region_id = r.id
                LEFT JOIN (
                    SELECT ville_id,
                           SUM(quantite * prix_unitaire) AS total_valeur,
                           SUM(quantite) AS total_qte
                    FROM besoin GROUP BY ville_id
                ) besoins_agg ON besoins_agg.ville_id = v.id
                LEFT JOIN (
                    SELECT b.ville_id,
                           SUM(di.montant_attribue) AS total_attribue,
                           SUM(di.quantite_attribuee) AS total_qte_attribuee
                    FROM dispatch di
                    JOIN besoin b ON di.besoin_id = b.id
                    GROUP BY b.ville_id
                ) dispatch_agg ON dispatch_agg.ville_id = v.id
                ORDER BY r.nom, v.nom';
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['reste_a_couvrir'] = $row['valeur_besoins'] - $row['valeur_attribuee'];
        }
        return $rows;
    }

    /**
     * Simuler le dispatch automatique des dons vers les besoins
     * SANS enregistrer - retourne une prévisualisation
     */
    public static function simulerDispatchPreview()
    {
        $db = Flight::db();

        // Récupérer les dons non totalement dispatchés, par ordre chronologique
        $donsSql = 'SELECT d.id, d.type_besoin_id, d.designation, d.quantite, d.montant,
                           COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante,
                           COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) AS mnt_restant,
                           tb.libelle AS type_libelle
                    FROM dons d
                    JOIN type_besoin tb ON d.type_besoin_id = tb.id
                    LEFT JOIN dispatch di ON di.don_id = d.id
                    GROUP BY d.id
                    HAVING qte_restante > 0 OR mnt_restant > 0
                    ORDER BY d.date_don ASC, d.id ASC';
        $dons = $db->query($donsSql)->fetchAll();

        // Récupérer les besoins non totalement couverts, par ordre chronologique
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.description, b.prix_unitaire, b.quantite_restante,
                              v.nom AS ville, tb.libelle AS type_libelle
                       FROM besoin b
                       JOIN villes v ON b.ville_id = v.id
                       JOIN type_besoin tb ON b.type_besoin_id = tb.id
                       WHERE b.quantite_restante > 0
                       ORDER BY b.date_creation ASC, b.id ASC';
        $besoins = $db->query($besoinsSql)->fetchAll();

        $simulation = [];

        // Copier pour ne pas modifier les originaux
        $besoinsLocal = $besoins;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];

            foreach ($besoinsLocal as &$besoin) {
                if ($besoin['quantite_restante'] <= 0) continue;
                if ($don['type_besoin_id'] != $besoin['type_besoin_id']) continue;

                $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                if ($qteAttribuer <= 0) continue;

                $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];

                $simulation[] = [
                    'don_id'              => $don['id'],
                    'don_designation'     => $don['designation'],
                    'don_type'            => $don['type_libelle'],
                    'besoin_id'           => $besoin['id'],
                    'besoin_description'  => $besoin['description'],
                    'ville'               => $besoin['ville'],
                    'quantite_attribuee'  => $qteAttribuer,
                    'montant_attribue'    => $mntAttribuer,
                ];

                $besoin['quantite_restante'] -= $qteAttribuer;
                $qteDisponible -= $qteAttribuer;

                if ($qteDisponible <= 0) break;
            }
        }

        return $simulation;
    }

    /**
     * Exécuter réellement le dispatch (enregistrer en base)
     */
    public static function executerDispatch()
    {
        $db = Flight::db();

        // Récupérer les dons non totalement dispatchés, par ordre chronologique
        $donsSql = 'SELECT d.id, d.type_besoin_id, d.designation, d.quantite, d.montant,
                           COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante,
                           COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) AS mnt_restant
                    FROM dons d
                    LEFT JOIN dispatch di ON di.don_id = d.id
                    GROUP BY d.id
                    HAVING qte_restante > 0 OR mnt_restant > 0
                    ORDER BY d.date_don ASC, d.id ASC';
        $dons = $db->query($donsSql)->fetchAll();

        // Récupérer les besoins non totalement couverts, par ordre chronologique
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.prix_unitaire, b.quantite_restante
                       FROM besoin b
                       WHERE b.quantite_restante > 0
                       ORDER BY b.date_creation ASC, b.id ASC';
        $besoins = $db->query($besoinsSql)->fetchAll();

        $dispatches = 0;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];

            foreach ($besoins as &$besoin) {
                if ($besoin['quantite_restante'] <= 0) continue;
                if ($don['type_besoin_id'] != $besoin['type_besoin_id']) continue;

                $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                if ($qteAttribuer <= 0) continue;

                $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];

                self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                BesoinModel::updateQuantiteRestante($besoin['id'], $besoin['quantite_restante'] - $qteAttribuer);

                $besoin['quantite_restante'] -= $qteAttribuer;
                $qteDisponible -= $qteAttribuer;
                $dispatches++;

                if ($qteDisponible <= 0) break;
            }
        }

        return $dispatches;
    }
}
