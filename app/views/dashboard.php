<?php
$page_title = 'Tableau de bord';
$active = 'dashboard';
include __DIR__ . '/layout/header.php';
?>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
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
    ?>
    <div class="stat-card bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Villes</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= $nbVilles ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-city text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Besoins totaux</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($totalBesoins, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-clipboard-list text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Dons attribués</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($totalAttribue, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-hand-holding-heart text-green-600 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="stat-card bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Reste à couvrir</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($totalReste, 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tableau situation par ville -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fa-solid fa-map-location-dot mr-2 text-blue-600"></i>
            Situation par ville
        </h3>
        <a href="/dispatch/simuler" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
            <i class="fa-solid fa-play mr-2"></i>
            Simuler le dispatch
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Région</th>
                    <th class="px-6 py-3">Ville</th>
                    <th class="px-6 py-3 text-right">Besoins (valeur)</th>
                    <th class="px-6 py-3 text-right">Dons attribués</th>
                    <th class="px-6 py-3 text-right">Reste à couvrir</th>
                    <th class="px-6 py-3 text-center">Couverture</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($situation)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            Aucune donnée pour le moment
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($situation as $i => $s): ?>
                        <?php
                        $pct = $s['valeur_besoins'] > 0
                            ? round(($s['valeur_attribuee'] / $s['valeur_besoins']) * 100)
                            : 0;
                        $barColor = $pct >= 75 ? 'bg-green-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-red-500');
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                            <td class="px-6 py-3 text-sm">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= htmlspecialchars($s['region']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-800"><?= htmlspecialchars($s['ville']) ?></td>
                            <td class="px-6 py-3 text-sm text-right text-gray-700"><?= number_format($s['valeur_besoins'], 0, ',', ' ') ?> Ar</td>
                            <td class="px-6 py-3 text-sm text-right text-green-600 font-medium"><?= number_format($s['valeur_attribuee'], 0, ',', ' ') ?> Ar</td>
                            <td class="px-6 py-3 text-sm text-right <?= $s['reste_a_couvrir'] > 0 ? 'text-red-600 font-medium' : 'text-green-600' ?>">
                                <?= number_format($s['reste_a_couvrir'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="<?= $barColor ?> h-2 rounded-full transition-all" style="width: <?= min($pct, 100) ?>%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 w-10 text-right"><?= $pct ?>%</span>
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
