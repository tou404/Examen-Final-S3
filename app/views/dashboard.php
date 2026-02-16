<?php
$page_title = 'Tableau de bord';
$active = 'dashboard';
include __DIR__ . '/layout/header.php';
?>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <?php
    $totalBesoins = 0;
    $totalAttribue = 0;
    $totalReste = 0;
    $nbVilles = count($situation);
    foreach ($situation as $s) {
        $totalBesoins += $s['valeur_besoins'];
        $totalAttribue += $s['valeur_attribuee'];
        $totalReste += $s['reste_a_couvrir'];
    }
    $pctGlobal = $totalBesoins > 0 ? round(($totalAttribue / $totalBesoins) * 100) : 0;
    ?>
    
    <!-- Card Villes -->
    <div class="stat-card bg-white rounded-2xl shadow-card p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-cyan-500/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-start justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold uppercase tracking-wider bg-blue-50 text-blue-600">
                    Villes
                </span>
                <p class="text-4xl font-extrabold text-slate-800"><?= $nbVilles ?></p>
                <p class="text-sm text-slate-500">zones couvertes</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-city text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Besoins -->
    <div class="stat-card bg-white rounded-2xl shadow-card p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-500/10 to-orange-500/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-start justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold uppercase tracking-wider bg-amber-50 text-amber-600">
                    Besoins totaux
                </span>
                <p class="text-3xl font-extrabold text-slate-800"><?= number_format($totalBesoins, 0, ',', ' ') ?></p>
                <p class="text-sm text-slate-500">Ariary</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                <i class="fa-solid fa-clipboard-list text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Dons attribués -->
    <div class="stat-card bg-white rounded-2xl shadow-card p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-green-500/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-start justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-600">
                    Dons attribués
                </span>
                <p class="text-3xl font-extrabold text-slate-800"><?= number_format($totalAttribue, 0, ',', ' ') ?></p>
                <p class="text-sm text-slate-500">Ariary distribués</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <i class="fa-solid fa-hand-holding-heart text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Reste -->
    <div class="stat-card bg-white rounded-2xl shadow-card p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-rose-500/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative flex items-start justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold uppercase tracking-wider bg-red-50 text-red-600">
                    Reste à couvrir
                </span>
                <p class="text-3xl font-extrabold text-slate-800"><?= number_format($totalReste, 0, ',', ' ') ?></p>
                <p class="text-sm text-slate-500">Ariary manquants</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-500/30">
                <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barre de progression globale -->
<div class="bg-white rounded-2xl shadow-card p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Progression globale</h3>
            <p class="text-sm text-slate-500">Couverture des besoins par les dons</p>
        </div>
        <span class="text-3xl font-extrabold <?= $pctGlobal >= 75 ? 'text-emerald-600' : ($pctGlobal >= 40 ? 'text-amber-600' : 'text-red-600') ?>"><?= $pctGlobal ?>%</span>
    </div>
    <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-1000 ease-out <?= $pctGlobal >= 75 ? 'bg-gradient-to-r from-emerald-400 to-green-500' : ($pctGlobal >= 40 ? 'bg-gradient-to-r from-amber-400 to-orange-500' : 'bg-gradient-to-r from-red-400 to-rose-500') ?>" style="width: <?= min($pctGlobal, 100) ?>%"></div>
    </div>
</div>

<!-- Tableau situation par ville -->
<div class="bg-white rounded-2xl shadow-card overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-map-location-dot text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Situation par ville</h3>
                <p class="text-sm text-slate-500">Détail des besoins et attributions</p>
            </div>
        </div>
        <a href="/dispatch/simuler" class="btn-success inline-flex items-center px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition-all">
            <i class="fa-solid fa-play mr-2"></i>
            Simuler le dispatch
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-pro">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Région</th>
                    <th class="px-6 py-4">Ville</th>
                    <th class="px-6 py-4 text-right">Besoins</th>
                    <th class="px-6 py-4 text-right">Attribués</th>
                    <th class="px-6 py-4 text-right">Reste</th>
                    <th class="px-6 py-4">Couverture</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($situation)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-inbox text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Aucune donnée disponible</p>
                                <p class="text-slate-400 text-sm mt-1">Commencez par ajouter des villes et des besoins</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($situation as $i => $s): ?>
                        <?php
                        $pct = $s['valeur_besoins'] > 0
                            ? round(($s['valeur_attribuee'] / $s['valeur_besoins']) * 100)
                            : 0;
                        $barGradient = $pct >= 75 ? 'from-emerald-400 to-green-500' : ($pct >= 40 ? 'from-amber-400 to-orange-500' : 'from-red-400 to-rose-500');
                        $textColor = $pct >= 75 ? 'text-emerald-600' : ($pct >= 40 ? 'text-amber-600' : 'text-red-600');
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?= $i + 1 ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                    <i class="fa-solid fa-map-pin mr-1.5 text-blue-400"></i>
                                    <?= htmlspecialchars($s['region']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($s['ville']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-slate-700"><?= number_format($s['valeur_besoins'], 0, ',', ' ') ?> <span class="text-slate-400">Ar</span></td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-emerald-600"><?= number_format($s['valeur_attribuee'], 0, ',', ' ') ?> <span class="text-emerald-400">Ar</span></td>
                            <td class="px-6 py-4 text-sm text-right font-semibold <?= $s['reste_a_couvrir'] > 0 ? 'text-red-600' : 'text-emerald-600' ?>">
                                <?= number_format($s['reste_a_couvrir'], 0, ',', ' ') ?> <span class="<?= $s['reste_a_couvrir'] > 0 ? 'text-red-400' : 'text-emerald-400' ?>">Ar</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-1 w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r <?= $barGradient ?> transition-all duration-500" style="width: <?= min($pct, 100) ?>%"></div>
                                    </div>
                                    <span class="text-xs font-bold <?= $textColor ?> w-10 text-right"><?= $pct ?>%</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
