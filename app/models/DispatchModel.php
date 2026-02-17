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
    public static function simulerDispatchPreview($mode = 'ordre')
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
                    ORDER BY qte_restante ASC, d.id ASC';
        $dons = $db->query($donsSql)->fetchAll();


         // Récupérer les besoins non totalement couverts, du plus petit au plus grand
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.description, b.prix_unitaire, b.quantite_restante,
                              v.nom AS ville, tb.libelle AS type_libelle,
                              CASE WHEN b.type_besoin_id = 3 THEN (b.prix_unitaire - COALESCE((SELECT SUM(montant_attribue) FROM dispatch WHERE besoin_id = b.id),0)) ELSE NULL END AS montant_rest
                       FROM besoin b
                       JOIN villes v ON b.ville_id = v.id
                       JOIN type_besoin tb ON b.type_besoin_id = tb.id
                       WHERE (b.quantite_restante > 0 OR b.type_besoin_id = 3)
                       ORDER BY b.quantite_restante ASC, b.id ASC';
        $besoins = $db->query($besoinsSql)->fetchAll();


        $simulation = [];

        // Copier pour ne pas modifier les originaux
        $besoinsLocal = $besoins;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];
            $mntDisponible = (float) $don['mnt_restant'];

            // Filtrer besoins correspondant au type
            $besoinsFiltres = array_values(array_filter($besoinsLocal, function ($b) use ($don) {
                if ($b['type_besoin_id'] != $don['type_besoin_id']) return false;
                if ($b['type_besoin_id'] == 3) {
                    // besoin en argent
                    return ($b['montant_rest'] ?? 0) > 0;
                }
                return $b['quantite_restante'] > 0;
            }));

            if (empty($besoinsFiltres)) continue;

            // Mode: ordre (par date) -> garder l'ordre naturel (id asc)
            if ($mode === 'min_need') {
                // trier par besoin le plus petit d'abord
                usort($besoinsFiltres, function ($a, $b) {
                    return $a['quantite_restante'] <=> $b['quantite_restante'];
                });
            } elseif ($mode === 'proportionnel') {
                // pour proportionnel on calculera les parts ci-dessous
                // garder la liste telle quelle
            } else {
                // 'ordre' ou autre -> trier par id (croissant)
                usort($besoinsFiltres, function ($a, $b) {
                    return $a['id'] <=> $b['id'];
                });
            }

                if ($mode === 'proportionnel') {
                    if ($don['type_besoin_id'] == 3) {
                        // --- Proportionnel pour dons en ARGENT ---
                        $totalBesoin = array_sum(array_column($besoinsFiltres, 'montant_rest')) ?: 0;
                        if ($totalBesoin <= 0 || $mntDisponible <= 0) continue;

                        // Étape 1 : calculer la part exacte et arrondir au floor
                        $attributions = [];
                        $totalFloor = 0;
                        foreach ($besoinsFiltres as $k => $besoin) {
                            $exact = (($besoin['montant_rest'] ?? 0) / $totalBesoin) * $mntDisponible;
                            $floored = floor($exact);
                            $decimal = $exact - $floored;
                            $attributions[$k] = [
                                'besoin' => $besoin,
                                'exact'  => $exact,
                                'floor'  => $floored,
                                'decimal' => $decimal,
                            ];
                            $totalFloor += $floored;
                        }

                        // Étape 2 : distribuer le reste aux plus grands restes décimaux
                        $reste = $mntDisponible - $totalFloor;
                        usort($attributions, function ($a, $b) {
                            return $b['decimal'] <=> $a['decimal'];
                        });
                        foreach ($attributions as &$attr) {
                            if ($reste <= 0) break;
                            if ($attr['floor'] >= 0 && $attr['decimal'] > 0) {
                                $attr['floor']++;
                                $reste--;
                            }
                        }
                        unset($attr);

                        // Étape 3 : enregistrer les attributions
                        foreach ($attributions as $attr) {
                            $mntAttribuer = $attr['floor'];
                            if ($mntAttribuer <= 0) continue;
                            $besoin = $attr['besoin'];

                            $simulation[] = [
                                'don_id'              => $don['id'],
                                'don_designation'     => $don['designation'],
                                'don_type'            => $don['type_libelle'],
                                'besoin_id'           => $besoin['id'],
                                'besoin_description'  => $besoin['description'],
                                'ville'               => $besoin['ville'],
                                'quantite_attribuee'  => null,
                                'montant_attribue'    => $mntAttribuer,
                            ];
                            foreach ($besoinsLocal as &$bref) {
                                if ($bref['id'] == $besoin['id']) {
                                    $bref['montant_rest'] = ($bref['montant_rest'] ?? 0) - $mntAttribuer;
                                    break;
                                }
                            }
                            unset($bref);
                        }
                    } else {
                        // --- Proportionnel pour dons en NATURE / MATÉRIAUX ---
                        $totalBesoin = array_sum(array_column($besoinsFiltres, 'quantite_restante')) ?: 0;
                        if ($totalBesoin <= 0) continue;

                        // Étape 1 : calculer la part exacte et arrondir au floor
                        $attributions = [];
                        $totalFloor = 0;
                        foreach ($besoinsFiltres as $k => $besoin) {
                            $exact = ($besoin['quantite_restante'] / $totalBesoin) * $qteDisponible;
                            $floored = floor($exact);
                            $decimal = $exact - $floored;
                            $attributions[$k] = [
                                'besoin' => $besoin,
                                'exact'  => $exact,
                                'floor'  => $floored,
                                'decimal' => $decimal,
                            ];
                            $totalFloor += $floored;
                        }

                        // Étape 2 : distribuer le reste (1 par 1) aux plus grands restes décimaux
                        $reste = $qteDisponible - $totalFloor;
                        usort($attributions, function ($a, $b) {
                            return $b['decimal'] <=> $a['decimal'];
                        });
                        foreach ($attributions as &$attr) {
                            if ($reste <= 0) break;
                            if ($attr['floor'] >= 0 && $attr['decimal'] > 0) {
                                $attr['floor']++;
                                $reste--;
                            }
                        }
                        unset($attr);

                        // Étape 3 : enregistrer les attributions
                        foreach ($attributions as $attr) {
                            $qteAttribuer = $attr['floor'];
                            if ($qteAttribuer <= 0) continue;
                            $besoin = $attr['besoin'];

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
                            foreach ($besoinsLocal as &$bref) {
                                if ($bref['id'] == $besoin['id']) {
                                    $bref['quantite_restante'] -= $qteAttribuer;
                                    break;
                                }
                            }
                            unset($bref);
                        }
                    }

                } else {
                    // mode 'ordre' ou 'min_need' : attribuer séquentiellement
                    foreach ($besoinsFiltres as $besoin) {
                        if ($don['type_besoin_id'] == 3) {
                            if ($mntDisponible <= 0) break;
                            $need = $besoin['montant_rest'] ?? 0;
                            if ($need <= 0) continue;
                            $mntAttribuer = min($mntDisponible, $need);
                            if ($mntAttribuer <= 0) continue;

                            $simulation[] = [
                                'don_id'              => $don['id'],
                                'don_designation'     => $don['designation'],
                                'don_type'            => $don['type_libelle'],
                                'besoin_id'           => $besoin['id'],
                                'besoin_description'  => $besoin['description'],
                                'ville'               => $besoin['ville'],
                                'quantite_attribuee'  => null,
                                'montant_attribue'    => $mntAttribuer,
                            ];

                            foreach ($besoinsLocal as &$bref) {
                                if ($bref['id'] == $besoin['id']) {
                                    $bref['montant_rest'] = ($bref['montant_rest'] ?? 0) - $mntAttribuer;
                                    break;
                                }
                            }
                            $mntDisponible -= $mntAttribuer;
                        } else {
                            if ($qteDisponible <= 0) break;
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

                            // mettre à jour les besoins locaux
                            foreach ($besoinsLocal as &$bref) {
                                if ($bref['id'] == $besoin['id']) {
                                    $bref['quantite_restante'] -= $qteAttribuer;
                                    break;
                                }
                            }

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

        // Récupérer les dons non totalement dispatchés, par ordre chronologique
        $donsSql = 'SELECT d.id, d.type_besoin_id, d.designation, d.quantite, d.montant,
                           COALESCE(d.quantite, 0) - COALESCE(SUM(di.quantite_attribuee), 0) AS qte_restante,
                           COALESCE(d.montant, 0) - COALESCE(SUM(di.montant_attribue), 0) AS mnt_restant
                    FROM dons d
                    LEFT JOIN dispatch di ON di.don_id = d.id
                    GROUP BY d.id
                    HAVING qte_restante > 0 OR mnt_restant > 0
                    ORDER BY qte_restante ASC, d.id ASC';
        $dons = $db->query($donsSql)->fetchAll();


        // Récupérer les besoins non totalement couverts, du plus petit au plus grand
        $besoinsSql = 'SELECT b.id, b.type_besoin_id, b.prix_unitaire, b.quantite_restante,
                       CASE WHEN b.type_besoin_id = 3 THEN (b.prix_unitaire - COALESCE((SELECT SUM(montant_attribue) FROM dispatch WHERE besoin_id = b.id),0)) ELSE NULL END AS montant_rest
                   FROM besoin b
                   WHERE (b.quantite_restante > 0 OR b.type_besoin_id = 3)
                   ORDER BY b.quantite_restante ASC, b.id ASC';
       $besoins = $db->query($besoinsSql)->fetchAll();

        $dispatches = 0;

        foreach ($dons as $don) {
            $qteDisponible = (float) $don['qte_restante'];
            $mntDisponible = (float) $don['mnt_restant'];

            // Filtrer besoins correspondant au type
            $besoinsFiltresIdx = [];
            foreach ($besoins as $idx => $b) {
                if ($b['type_besoin_id'] != $don['type_besoin_id']) continue;
                if ($b['type_besoin_id'] == 3) {
                    if (($b['montant_rest'] ?? 0) > 0) $besoinsFiltresIdx[] = $idx;
                } else {
                    if ($b['quantite_restante'] > 0) $besoinsFiltresIdx[] = $idx;
                }
            }

            if (empty($besoinsFiltresIdx)) continue;

            // Préparer ordre selon mode
            if ($mode === 'min_need') {
                usort($besoinsFiltresIdx, function ($a, $b) use ($besoins) {
                    return $besoins[$a]['quantite_restante'] <=> $besoins[$b]['quantite_restante'];
                });
            } elseif ($mode === 'ordre') {
                sort($besoinsFiltresIdx);
            }

            if ($mode === 'proportionnel') {
                // gérer quantité ou montant selon le type
                if ($don['type_besoin_id'] == 3) {
                    // --- Proportionnel ARGENT ---
                    $totalBesoin = 0;
                    foreach ($besoinsFiltresIdx as $idx) $totalBesoin += ($besoins[$idx]['montant_rest'] ?? 0);
                    if ($totalBesoin <= 0 || $mntDisponible <= 0) continue;

                    // Étape 1 : floor de chaque part
                    $attributions = [];
                    $totalFloor = 0;
                    foreach ($besoinsFiltresIdx as $idx) {
                        $exact = (($besoins[$idx]['montant_rest'] ?? 0) / $totalBesoin) * $mntDisponible;
                        $floored = floor($exact);
                        $decimal = $exact - $floored;
                        $attributions[] = ['idx' => $idx, 'floor' => $floored, 'decimal' => $decimal];
                        $totalFloor += $floored;
                    }

                    // Étape 2 : distribuer le reste aux restes décimaux les plus élevés
                    $reste = $mntDisponible - $totalFloor;
                    usort($attributions, function ($a, $b) { return $b['decimal'] <=> $a['decimal']; });
                    foreach ($attributions as &$attr) {
                        if ($reste <= 0) break;
                        if ($attr['decimal'] > 0) { $attr['floor']++; $reste--; }
                    }
                    unset($attr);

                    // Étape 3 : enregistrer
                    foreach ($attributions as $attr) {
                        $mntAttribuer = $attr['floor'];
                        if ($mntAttribuer <= 0) continue;
                        $besoin = &$besoins[$attr['idx']];
                        self::dispatch($don['id'], $besoin['id'], null, $mntAttribuer);
                        $besoin['montant_rest'] -= $mntAttribuer;
                        $dispatches++;
                    }
                } else {
                    // --- Proportionnel NATURE / MATÉRIAUX ---
                    $totalBesoin = 0;
                    foreach ($besoinsFiltresIdx as $idx) $totalBesoin += $besoins[$idx]['quantite_restante'];
                    if ($totalBesoin <= 0) continue;

                    // Étape 1 : floor de chaque part
                    $attributions = [];
                    $totalFloor = 0;
                    foreach ($besoinsFiltresIdx as $idx) {
                        $exact = ($besoins[$idx]['quantite_restante'] / $totalBesoin) * $qteDisponible;
                        $floored = floor($exact);
                        $decimal = $exact - $floored;
                        $attributions[] = ['idx' => $idx, 'floor' => $floored, 'decimal' => $decimal];
                        $totalFloor += $floored;
                    }

                    // Étape 2 : distribuer le reste aux restes décimaux les plus élevés
                    $reste = $qteDisponible - $totalFloor;
                    usort($attributions, function ($a, $b) { return $b['decimal'] <=> $a['decimal']; });
                    foreach ($attributions as &$attr) {
                        if ($reste <= 0) break;
                        if ($attr['decimal'] > 0) { $attr['floor']++; $reste--; }
                    }
                    unset($attr);

                    // Étape 3 : enregistrer
                    foreach ($attributions as $attr) {
                        $qteAttribuer = $attr['floor'];
                        if ($qteAttribuer <= 0) continue;
                        $besoin = &$besoins[$attr['idx']];
                        $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];
                        self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                        BesoinModel::updateQuantiteRestante($besoin['id'], $besoin['quantite_restante'] - $qteAttribuer);
                        $besoin['quantite_restante'] -= $qteAttribuer;
                        $dispatches++;
                    }
                }

            } else {
                // ordre ou min_need: boucle séquentielle
                foreach ($besoinsFiltresIdx as $idx) {
                    if ($don['type_besoin_id'] == 3) {
                        if ($mntDisponible <= 0) break;
                        $besoin = &$besoins[$idx];
                        $need = $besoin['montant_rest'] ?? 0;
                        if ($need <= 0) continue;
                        $mntAttribuer = min($mntDisponible, $need);
                        if ($mntAttribuer <= 0) continue;

                        self::dispatch($don['id'], $besoin['id'], null, $mntAttribuer);
                        $besoin['montant_rest'] -= $mntAttribuer;
                        $mntDisponible -= $mntAttribuer;
                        $dispatches++;
                    } else {
                        if ($qteDisponible <= 0) break;
                        $besoin = &$besoins[$idx];
                        if ($besoin['quantite_restante'] <= 0) continue;

                        $qteAttribuer = min($qteDisponible, (float) $besoin['quantite_restante']);
                        if ($qteAttribuer <= 0) continue;

                        $mntAttribuer = $qteAttribuer * (float) $besoin['prix_unitaire'];
                        self::dispatch($don['id'], $besoin['id'], $qteAttribuer, $mntAttribuer);
                        BesoinModel::updateQuantiteRestante($besoin['id'], $besoin['quantite_restante'] - $qteAttribuer);

                        $besoin['quantite_restante'] -= $qteAttribuer;
                        $qteDisponible -= $qteAttribuer;
                        $dispatches++;
                    }
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
