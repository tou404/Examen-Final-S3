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
     * 
     * Mode 1 (ordre)       : dons par date, besoins par id (ordre de saisie)
     * Mode 2 (min_need)    : dons par date, besoins par quantite_restante ASC (plus petit besoin d'abord)
     * Mode 3 (proportionnel): dons par date, répartition proportionnelle aux besoins restants
     */
    public static function simulerDispatchPreview($mode = 'ordre')
    {
        $db = Flight::db();

        // Récupérer les dons non totalement dispatchés, par ordre chronologique (date de don)
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

        // Récupérer les besoins non totalement couverts
        // Pour les besoins en nature/matériel : quantite_restante > 0
        // Pour les besoins en argent (type 3) : on calcule le montant restant = (quantite * prix_unitaire) - somme(dispatches)
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.description, b.prix_unitaire, 
                              b.quantite, b.quantite_restante,
                              v.nom AS ville, tb.libelle AS type_libelle,
                              (b.quantite * b.prix_unitaire) - COALESCE((SELECT SUM(montant_attribue) FROM dispatch WHERE besoin_id = b.id), 0) AS montant_rest
                       FROM besoin b
                       JOIN villes v ON b.ville_id = v.id
                       JOIN type_besoin tb ON b.type_besoin_id = tb.id
                       WHERE b.quantite_restante > 0
                       ORDER BY b.id ASC';
        $besoins = $db->query($besoinsSql)->fetchAll();

        $simulation = [];

        // Copie locale des besoins pour la simulation (ne modifie pas la base)
        $besoinsLocal = [];
        foreach ($besoins as $b) {
            $besoinsLocal[$b['id']] = $b;
        }

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];
            $mntDisponible = (float) $don['mnt_restant'];

            // Filtrer les besoins du même type avec encore du restant
            $besoinsFiltresIds = [];
            foreach ($besoinsLocal as $b) {
                if ($b['type_besoin_id'] != $don['type_besoin_id']) continue;
                if ($b['quantite_restante'] <= 0) continue;
                $besoinsFiltresIds[] = $b['id'];
            }

            if (empty($besoinsFiltresIds) || $qteDisponible <= 0) continue;

            // Trier les IDs selon le mode (en lisant les valeurs actuelles de $besoinsLocal)
            if ($mode === 'min_need') {
                usort($besoinsFiltresIds, function ($aId, $bId) use ($besoinsLocal) {
                    return $besoinsLocal[$aId]['quantite_restante'] <=> $besoinsLocal[$bId]['quantite_restante'];
                });
            } elseif ($mode === 'ordre') {
                sort($besoinsFiltresIds); // tri par id ASC = ordre de saisie
            }
            // proportionnel : pas de tri spécifique

            if ($mode === 'proportionnel') {
                // Répartition proportionnelle de la quantité selon les besoins restants
                $totalBesoin = 0;
                foreach ($besoinsFiltresIds as $bid) {
                    $totalBesoin += $besoinsLocal[$bid]['quantite_restante'];
                }
                if ($totalBesoin <= 0) continue;

                foreach ($besoinsFiltresIds as $bid) {
                    if ($qteDisponible <= 0) break;
                    $besoin = $besoinsLocal[$bid];
                    if ($besoin['quantite_restante'] <= 0) continue;

                    $share = $besoin['quantite_restante'] / $totalBesoin;
                    $qteAttribuer = floor($qteDisponible * $share);
                    // Au moins 1 si possible
                    if ($qteAttribuer <= 0 && $qteDisponible > 0 && $besoin['quantite_restante'] > 0) {
                        $qteAttribuer = 1;
                    }
                    $qteAttribuer = min($qteAttribuer, $besoin['quantite_restante']);
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

                    $besoinsLocal[$bid]['quantite_restante'] -= $qteAttribuer;
                    $qteDisponible -= $qteAttribuer;
                }
            } else {
                // Mode 'ordre' ou 'min_need' : attribution séquentielle
                foreach ($besoinsFiltresIds as $bid) {
                    if ($qteDisponible <= 0) break;
                    $besoin = $besoinsLocal[$bid];
                    if ($besoin['quantite_restante'] <= 0) continue;

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

                    $besoinsLocal[$bid]['quantite_restante'] -= $qteAttribuer;
                    $qteDisponible -= $qteAttribuer;
                }
            }
        }

        return $simulation;
    }

    /**
     * Exécuter réellement le dispatch (enregistrer en base)
     */
    public static function executerDispatch($mode = 'ordre')
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

        // Récupérer les besoins non totalement couverts
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.prix_unitaire, b.quantite, b.quantite_restante
                       FROM besoin b
                       WHERE b.quantite_restante > 0
                       ORDER BY b.id ASC';
        $besoins = $db->query($besoinsSql)->fetchAll();

        // Indexer par id pour accès rapide
        $besoinsById = [];
        foreach ($besoins as $b) {
            $besoinsById[$b['id']] = $b;
        }

        $dispatches = 0;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];

            // Filtrer besoins du même type avec du restant — on travaille avec les IDs
            $besoinsFiltresIds = [];
            foreach ($besoinsById as $b) {
                if ($b['type_besoin_id'] != $don['type_besoin_id']) continue;
                if ($b['quantite_restante'] <= 0) continue;
                $besoinsFiltresIds[] = $b['id'];
            }

            if (empty($besoinsFiltresIds) || $qteDisponible <= 0) continue;

            // Trier les IDs selon le mode (en lisant les valeurs actuelles de $besoinsById)
            if ($mode === 'min_need') {
                usort($besoinsFiltresIds, function ($aId, $bId) use ($besoinsById) {
                    return $besoinsById[$aId]['quantite_restante'] <=> $besoinsById[$bId]['quantite_restante'];
                });
            } elseif ($mode === 'ordre') {
                sort($besoinsFiltresIds); // tri par id ASC = ordre de saisie
            }

            if ($mode === 'proportionnel') {
                $totalBesoin = 0;
                foreach ($besoinsFiltresIds as $bid) {
                    $totalBesoin += $besoinsById[$bid]['quantite_restante'];
                }
                if ($totalBesoin <= 0) continue;

                foreach ($besoinsFiltresIds as $bid) {
                    if ($qteDisponible <= 0) break;
                    $besoin = $besoinsById[$bid];
                    if ($besoin['quantite_restante'] <= 0) continue;

                    $share = $besoin['quantite_restante'] / $totalBesoin;
                    $qteAttribuer = floor($qteDisponible * $share);
                    if ($qteAttribuer <= 0 && $qteDisponible > 0 && $besoin['quantite_restante'] > 0) {
                        $qteAttribuer = 1;
                    }
                    $qteAttribuer = min($qteAttribuer, $besoin['quantite_restante']);
                    if ($qteAttribuer <= 0) continue;

                    $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];
                    self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                    BesoinModel::updateQuantiteRestante($besoin['id'], $besoinsById[$bid]['quantite_restante'] - $qteAttribuer);

                    $besoinsById[$bid]['quantite_restante'] -= $qteAttribuer;
                    $qteDisponible -= $qteAttribuer;
                    $dispatches++;
                }
            } else {
                // Mode 'ordre' ou 'min_need' : attribution séquentielle
                foreach ($besoinsFiltresIds as $bid) {
                    if ($qteDisponible <= 0) break;
                    $besoin = $besoinsById[$bid];
                    if ($besoin['quantite_restante'] <= 0) continue;

                    $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                    if ($qteAttribuer <= 0) continue;

                    $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];
                    self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                    BesoinModel::updateQuantiteRestante($besoin['id'], $besoinsById[$bid]['quantite_restante'] - $qteAttribuer);

                    $besoinsById[$bid]['quantite_restante'] -= $qteAttribuer;
                    $qteDisponible -= $qteAttribuer;
                    $dispatches++;
                }
            }
        }

        return $dispatches;
    }

    /**
     * Réinitialiser tous les dispatches et remettre les besoins à leur quantité initiale
     */
    public static function resetDispatches()
    {
        $db = Flight::db();
        $db->exec('DELETE FROM dispatch');
        $db->exec('UPDATE besoin SET quantite_restante = quantite');
        return true;
    }
}
