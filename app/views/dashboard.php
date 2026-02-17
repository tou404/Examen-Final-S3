<?php
$page_title = 'Tableau de bord';
$active = 'dashboard';
include __DIR__ . '/layout/header.php';

$totalBesoins = 0; $totalAttribue = 0; $totalReste = 0; $nbVilles = count($situation);
foreach ($situation as $s) {
    $totalBesoins += $s['valeur_besoins'];
    $totalAttribue += $s['valeur_attribuee'];
    $totalReste += $s['reste_a_couvrir'];
}
$pctGlobal = $totalBesoins > 0 ? round(($totalAttribue / $totalBesoins) * 100) : 0;
?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="stat-card animate-fade-in">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                <i class="fa-regular fa-building text-brand-500 text-lg"></i>
            </div>
            <span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400">
                <i class="fa-regular fa-circle-check mr-1"></i>Actif
            </span>
        </div>
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Villes</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= $nbVilles ?></p>
        <p class="text-xs text-gray-400 mt-1">zones couvertes</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                <i class="fa-regular fa-clipboard text-amber-600 text-lg"></i>
            </div>
            <span class="badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">Total</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Besoins</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= number_format($totalBesoins, 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400 mt-1">Ariary</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fa-regular fa-heart text-emerald-600 text-lg"></i>
            </div>
            <span class="badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"><?= $pctGlobal ?>%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Dons attribués</p>
        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1"><?= number_format($totalAttribue, 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400 mt-1">Ariary distribués</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                <i class="fa-regular fa-bell text-red-500 text-lg"></i>
            </div>
            <span class="badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">Urgent</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Reste à couvrir</p>
        <p class="text-2xl font-bold text-red-500 dark:text-red-400 mt-1"><?= number_format($totalReste, 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400 mt-1">Ariary manquants</p>
    </div>
</div>

<!-- Progression globale -->
<div class="card p-6 mb-6 animate-fade-in" style="animation-delay: 0.2s">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Progression globale</h3>
            <p class="text-xs text-gray-400 mt-0.5">Couverture des besoins par les dons</p>
        </div>
        <span class="text-2xl font-bold <?= $pctGlobal >= 75 ? 'text-emerald-600' : ($pctGlobal >= 40 ? 'text-amber-600' : 'text-red-500') ?>"><?= $pctGlobal ?>%</span>
    </div>
    <div class="progress-bar">
        <div class="fill <?= $pctGlobal >= 75 ? 'fill-green' : ($pctGlobal >= 40 ? 'fill-amber' : 'fill-red') ?>" style="width: <?= min($pctGlobal, 100) ?>%"></div>
    </div>
    <div class="flex justify-between text-[10px] text-gray-300 dark:text-gray-600 mt-2 font-medium">
        <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span>
    </div>
</div>

<!-- Tableau situation par ville -->
<div class="card overflow-hidden animate-fade-in" style="animation-delay: 0.25s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                <i class="fa-regular fa-map text-brand-600 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Situation par ville</h3>
                <p class="text-xs text-gray-400">Détail des besoins et attributions</p>
            </div>
        </div>
        <a href="/dispatch/simuler" class="btn btn-success text-xs py-2 px-4">
            <i class="fa-regular fa-circle-play mr-2"></i>Simuler
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Région</th>
                    <th class="text-left">Ville</th>
                    <th class="text-right">Besoins</th>
                    <th class="text-right">Attribués</th>
                    <th class="text-right">Reste</th>
                    <th class="text-left">Couverture</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($situation)): ?>
                    <tr><td colspan="7" class="empty-state text-center">
                        <div class="empty-icon"><i class="fa-regular fa-folder-open text-gray-400 text-xl"></i></div>
                        <p class="text-sm font-semibold text-gray-400">Aucune donnée disponible</p>
                        <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">Ajoutez des villes et des besoins</p>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($situation as $i => $s): ?>
                        <?php
                        $pct = $s['valeur_besoins'] > 0 ? round(($s['valeur_attribuee'] / $s['valeur_besoins']) * 100) : 0;
                        $fillClass = $pct >= 75 ? 'fill-green' : ($pct >= 40 ? 'fill-amber' : 'fill-red');
                        $textColor = $pct >= 75 ? 'text-emerald-600' : ($pct >= 40 ? 'text-amber-600' : 'text-red-500');
                        ?>
                        <tr>
                            <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                            <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($s['region']) ?></span></td>
                            <td class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($s['ville']) ?></td>
                            <td class="text-right text-gray-600 dark:text-gray-300 font-medium"><?= number_format($s['valeur_besoins'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-right font-bold text-emerald-600"><?= number_format($s['valeur_attribuee'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-right font-bold <?= $s['reste_a_couvrir'] > 0 ? 'text-red-500' : 'text-emerald-600' ?>"><?= number_format($s['reste_a_couvrir'], 0, ',', ' ') ?> Ar</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-1 w-20 progress-bar">
                                        <div class="fill <?= $fillClass ?>" style="width: <?= min($pct, 100) ?>%"></div>
                                    </div>
                                    <span class="text-xs font-bold <?= $textColor ?> w-9 text-right"><?= $pct ?>%</span>
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
