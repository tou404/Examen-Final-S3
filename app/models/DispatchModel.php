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
     * Récupérer les dons disponibles (non totalement dispatchés)
     */
    private static function getDonsDisponibles($db, $withLabels = false)
    {
        $extra = $withLabels ? ', d.designation, tb.libelle AS type_libelle' : '';
        $join  = $withLabels ? 'JOIN type_besoin tb ON d.type_besoin_id = tb.id' : '';
        $sql = "SELECT d.id, d.type_besoin_id, d.quantite, d.montant, d.date_don,
                       COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante,
                       COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) AS mnt_restant
                       $extra
                FROM dons d
                $join
                LEFT JOIN dispatch di ON di.don_id = d.id
                GROUP BY d.id
                HAVING qte_restante > 0 OR mnt_restant > 0
                ORDER BY d.date_don ASC, d.id ASC";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Récupérer les besoins non totalement couverts
     */
    private static function getBesoinsDisponibles($db, $withLabels = false)
    {
        $extra = $withLabels ? ', v.nom AS ville, tb.libelle AS type_libelle, b.description' : '';
        $join  = $withLabels
            ? 'JOIN villes v ON b.ville_id = v.id JOIN type_besoin tb ON b.type_besoin_id = tb.id'
            : '';
        $sql = "SELECT b.id, b.type_besoin_id, b.prix_unitaire, b.quantite, b.quantite_restante, b.date_creation,
                       (b.quantite * b.prix_unitaire) AS montant_total_besoin,
                       COALESCE((SELECT SUM(di2.montant_attribue) FROM dispatch di2 WHERE di2.besoin_id = b.id), 0) AS montant_deja_attribue,
                       (b.quantite * b.prix_unitaire) - COALESCE((SELECT SUM(di2.montant_attribue) FROM dispatch di2 WHERE di2.besoin_id = b.id), 0) AS montant_rest
                       $extra
                FROM besoin b
                $join
                WHERE b.quantite_restante > 0
                   OR (b.type_besoin_id = 3 AND (b.quantite * b.prix_unitaire) > COALESCE((SELECT SUM(di2.montant_attribue) FROM dispatch di2 WHERE di2.besoin_id = b.id), 0))
                ORDER BY b.date_creation ASC, b.id ASC";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Filtrer les besoins par type et disponibilité
     */
    private static function filtrerBesoins(&$besoins, $typeBesoinId)
    {
        $filtres = [];
        foreach ($besoins as $idx => $b) {
            if ($b['type_besoin_id'] != $typeBesoinId) continue;
            if ($typeBesoinId == 3) {
                if (($b['montant_rest'] ?? 0) > 0) $filtres[] = $idx;
            } else {
                if ($b['quantite_restante'] > 0) $filtres[] = $idx;
            }
        }
        return $filtres;
    }

    /**
     * Trier les indices de besoins selon le mode choisi
     * Mode 1 (ordre)    : par date_creation ASC → premiers demandeurs servis en premier
     * Mode 2 (min_need) : par quantité/montant croissant → les plus petits besoins d'abord
     * Mode 3 (proportionnel) : pas de tri spécial, le calcul proportionnel gère la distribution
     */
    private static function trierBesoinsParMode(&$besoinsFiltresIdx, &$besoins, $mode, $typeBesoinId)
    {
        if ($mode === 'ordre') {
            // Mode 1 : par date de création (les premiers demandeurs sont servis en premier)
            usort($besoinsFiltresIdx, function ($a, $b) use ($besoins) {
                $cmp = strcmp($besoins[$a]['date_creation'], $besoins[$b]['date_creation']);
                return $cmp !== 0 ? $cmp : ($besoins[$a]['id'] <=> $besoins[$b]['id']);
            });
        } elseif ($mode === 'min_need') {
            // Mode 2 : les plus petits besoins d'abord
            if ($typeBesoinId == 3) {
                // Pour l'argent, trier par montant restant croissant
                usort($besoinsFiltresIdx, function ($a, $b) use ($besoins) {
                    return ($besoins[$a]['montant_rest'] ?? 0) <=> ($besoins[$b]['montant_rest'] ?? 0);
                });
            } else {
                // Pour nature/matériaux, trier par quantité restante croissante
                usort($besoinsFiltresIdx, function ($a, $b) use ($besoins) {
                    return $besoins[$a]['quantite_restante'] <=> $besoins[$b]['quantite_restante'];
                });
            }
        }
        // Pour 'proportionnel', pas de tri nécessaire
    }

    /**
     * Distribution proportionnelle avec méthode des plus grands restes
     * Retourne un tableau [idx => quantité attribuée]
     */
    private static function calculerProportionnel($besoinsFiltresIdx, &$besoins, $disponible, $isArgent)
    {
        $champ = $isArgent ? 'montant_rest' : 'quantite_restante';

        // Calculer le total des besoins
        $totalBesoin = 0;
        foreach ($besoinsFiltresIdx as $idx) {
            $totalBesoin += (float)($besoins[$idx][$champ] ?? 0);
        }
        if ($totalBesoin <= 0 || $disponible <= 0) return [];

        // Étape 1 : floor de chaque part
        $attributions = [];
        $totalFloor = 0;
        foreach ($besoinsFiltresIdx as $idx) {
            $valeur = (float)($besoins[$idx][$champ] ?? 0);
            $exact = ($valeur / $totalBesoin) * $disponible;
            $floored = (int)floor($exact);
            $decimal = $exact - $floored;
            $attributions[] = ['idx' => $idx, 'floor' => $floored, 'decimal' => $decimal];
            $totalFloor += $floored;
        }

        // Étape 2 : distribuer le reste aux restes décimaux les plus élevés
        $reste = (int)$disponible - $totalFloor;
        usort($attributions, function ($a, $b) {
            return $b['decimal'] <=> $a['decimal'];
        });
        foreach ($attributions as &$attr) {
            if ($reste <= 0) break;
            if ($attr['decimal'] > 0) {
                $attr['floor']++;
                $reste--;
            }
        }
        unset($attr);

        return $attributions;
    }

    /**
     * Simuler le dispatch automatique des dons vers les besoins
     * SANS enregistrer - retourne une prévisualisation
     */
    public static function simulerDispatchPreview($mode = 'ordre')
    {
        $db = Flight::db();
        $dons = self::getDonsDisponibles($db, true);
        $besoins = self::getBesoinsDisponibles($db, true);

        $simulation = [];

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];
            $mntDisponible = (float) $don['mnt_restant'];
            $isArgent = ($don['type_besoin_id'] == 3);

            // Filtrer besoins du même type
            $besoinsFiltresIdx = self::filtrerBesoins($besoins, $don['type_besoin_id']);
            if (empty($besoinsFiltresIdx)) continue;

            // Trier selon le mode
            self::trierBesoinsParMode($besoinsFiltresIdx, $besoins, $mode, $don['type_besoin_id']);

            if ($mode === 'proportionnel') {
                $disponible = $isArgent ? $mntDisponible : $qteDisponible;
                $attributions = self::calculerProportionnel($besoinsFiltresIdx, $besoins, $disponible, $isArgent);

                foreach ($attributions as $attr) {
                    if ($attr['floor'] <= 0) continue;
                    $besoin = $besoins[$attr['idx']];

                    if ($isArgent) {
                        $simulation[] = [
                            'don_id'             => $don['id'],
                            'don_designation'    => $don['designation'],
                            'don_type'           => $don['type_libelle'],
                            'besoin_id'          => $besoin['id'],
                            'besoin_description' => $besoin['description'],
                            'ville'              => $besoin['ville'],
                            'quantite_attribuee' => null,
                            'montant_attribue'   => $attr['floor'],
                        ];
                        $besoins[$attr['idx']]['montant_rest'] -= $attr['floor'];
                    } else {
                        $mntAttribuer = $attr['floor'] * (float) $besoin['prix_unitaire'];
                        $simulation[] = [
                            'don_id'             => $don['id'],
                            'don_designation'    => $don['designation'],
                            'don_type'           => $don['type_libelle'],
                            'besoin_id'          => $besoin['id'],
                            'besoin_description' => $besoin['description'],
                            'ville'              => $besoin['ville'],
                            'quantite_attribuee' => $attr['floor'],
                            'montant_attribue'   => $mntAttribuer,
                        ];
                        $besoins[$attr['idx']]['quantite_restante'] -= $attr['floor'];
                    }
                }
            } else {
                // Mode 'ordre' ou 'min_need' : attribuer séquentiellement
                foreach ($besoinsFiltresIdx as $idx) {
                    $besoin = $besoins[$idx];

                    if ($isArgent) {
                        if ($mntDisponible <= 0) break;
                        $need = (float)($besoin['montant_rest'] ?? 0);
                        if ($need <= 0) continue;
                        $mntAttribuer = min($mntDisponible, $need);

                        $simulation[] = [
                            'don_id'             => $don['id'],
                            'don_designation'    => $don['designation'],
                            'don_type'           => $don['type_libelle'],
                            'besoin_id'          => $besoin['id'],
                            'besoin_description' => $besoin['description'],
                            'ville'              => $besoin['ville'],
                            'quantite_attribuee' => null,
                            'montant_attribue'   => $mntAttribuer,
                        ];
                        $besoins[$idx]['montant_rest'] -= $mntAttribuer;
                        $mntDisponible -= $mntAttribuer;
                    } else {
                        if ($qteDisponible <= 0) break;
                        if ($besoin['quantite_restante'] <= 0) continue;
                        $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                        $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];

                        $simulation[] = [
                            'don_id'             => $don['id'],
                            'don_designation'    => $don['designation'],
                            'don_type'           => $don['type_libelle'],
                            'besoin_id'          => $besoin['id'],
                            'besoin_description' => $besoin['description'],
                            'ville'              => $besoin['ville'],
                            'quantite_attribuee' => $qteAttribuer,
                            'montant_attribue'   => $mntAttribuer,
                        ];
                        $besoins[$idx]['quantite_restante'] -= $qteAttribuer;
                        $qteDisponible -= $qteAttribuer;
                    }
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
        $dons = self::getDonsDisponibles($db, false);
        $besoins = self::getBesoinsDisponibles($db, false);

        $dispatches = 0;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];
            $mntDisponible = (float) $don['mnt_restant'];
            $isArgent = ($don['type_besoin_id'] == 3);

            // Filtrer besoins du même type
            $besoinsFiltresIdx = self::filtrerBesoins($besoins, $don['type_besoin_id']);
            if (empty($besoinsFiltresIdx)) continue;

            // Trier selon le mode
            self::trierBesoinsParMode($besoinsFiltresIdx, $besoins, $mode, $don['type_besoin_id']);

            if ($mode === 'proportionnel') {
                $disponible = $isArgent ? $mntDisponible : $qteDisponible;
                $attributions = self::calculerProportionnel($besoinsFiltresIdx, $besoins, $disponible, $isArgent);

                foreach ($attributions as $attr) {
                    if ($attr['floor'] <= 0) continue;
                    $besoin = &$besoins[$attr['idx']];

                    if ($isArgent) {
                        self::dispatch($don['id'], $besoin['id'], null, $attr['floor']);
                        $besoin['montant_rest'] -= $attr['floor'];
                    } else {
                        $mntAttribuer = $attr['floor'] * (float) $besoin['prix_unitaire'];
                        self::dispatch($don['id'], $besoin['id'], $attr['floor'], $mntAttribuer);
                        BesoinModel::updateQuantiteRestante($besoin['id'], $besoin['quantite_restante'] - $attr['floor']);
                        $besoin['quantite_restante'] -= $attr['floor'];
                    }
                    $dispatches++;
                }
            } else {
                // Mode 'ordre' ou 'min_need' : attribuer séquentiellement
                foreach ($besoinsFiltresIdx as $idx) {
                    $besoin = &$besoins[$idx];

                    if ($isArgent) {
                        if ($mntDisponible <= 0) break;
                        $need = (float)($besoin['montant_rest'] ?? 0);
                        if ($need <= 0) continue;
                        $mntAttribuer = min($mntDisponible, $need);

                        self::dispatch($don['id'], $besoin['id'], null, $mntAttribuer);
                        $besoin['montant_rest'] -= $mntAttribuer;
                        $mntDisponible -= $mntAttribuer;
                    } else {
                        if ($qteDisponible <= 0) break;
                        if ($besoin['quantite_restante'] <= 0) continue;
                        $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                        $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];

                        self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                        BesoinModel::updateQuantiteRestante($besoin['id'], $besoin['quantite_restante'] - $qteAttribuer);
                        $besoin['quantite_restante'] -= $qteAttribuer;
                        $qteDisponible -= $qteAttribuer;
                    }
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
