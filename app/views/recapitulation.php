<?php
$page_title = 'Récapitulation';
$active = 'recap';
include __DIR__ . '/layout/header.php';
?>

<!-- En-tête -->
<div class="mb-6 card bg-brand-800 dark:bg-brand-950 p-6 animate-fade-in">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-white">
            <h2 class="text-lg font-bold mb-1 flex items-center">
                <i class="fa-regular fa-file-lines mr-3"></i>Récapitulation des besoins et dons
            </h2>
            <p class="text-blue-200/70 text-xs" id="last-update">
                <i class="fa-regular fa-clock mr-1"></i>Dernière mise à jour : <?= date('d/m/Y H:i:s') ?>
            </p>
        </div>
        <button onclick="actualiserStats()" id="btn-refresh"
                class="btn bg-white hover:bg-gray-50 text-brand-800 text-sm py-2.5 px-5">
            <i class="fa-solid fa-rotate mr-2" id="refresh-icon"></i>Actualiser
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="stat-card animate-fade-in" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                <i class="fa-regular fa-clipboard text-brand-500 text-lg"></i>
            </div>
            <span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400" id="stat-nb-besoins"><?= $stats['besoins_totaux']['nombre'] ?> besoins</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Besoins Totaux</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1" id="stat-besoins-totaux"><?= number_format($stats['besoins_totaux']['montant'], 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400">Ariary</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fa-regular fa-circle-check text-emerald-600 text-lg"></i>
            </div>
            <span class="badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400" id="stat-pourcentage"><?= $stats['pourcentage_couverture'] ?>%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Besoins Satisfaits</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1" id="stat-besoins-satisfaits"><?= number_format($stats['besoins_satisfaits']['montant'], 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400">Ariary</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
                <i class="fa-regular fa-bell text-red-500 text-lg"></i>
            </div>
            <span class="badge bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400" id="stat-qte-restante"><?= $stats['besoins_restants']['quantite'] ?> unités</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Besoins Restants</p>
        <p class="text-2xl font-bold text-red-500 mt-1" id="stat-besoins-restants"><?= number_format($stats['besoins_restants']['montant'], 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400">Ariary</p>
    </div>

    <div class="stat-card animate-fade-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                <i class="fa-regular fa-money-bill-1 text-amber-600 text-lg"></i>
            </div>
            <span class="badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">Disponible</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dons Argent</p>
        <p class="text-2xl font-bold text-amber-600 mt-1" id="stat-argent-restant"><?= number_format($stats['dons']['argent_restant'], 0, ',', ' ') ?></p>
        <p class="text-xs text-gray-400">Ariary</p>
    </div>
</div>

<!-- Barre de progression -->
<div class="card p-6 mb-6 animate-fade-in" style="animation-delay: 0.25s">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Progression globale</h3>
        <span class="text-2xl font-bold text-brand-600" id="progress-percentage"><?= $stats['pourcentage_couverture'] ?>%</span>
    </div>
    <div class="progress-bar">
        <div id="progress-bar" class="fill fill-blue" style="width: <?= min($stats['pourcentage_couverture'], 100) ?>%"></div>
    </div>
    <div class="flex justify-between text-[10px] text-gray-300 dark:text-gray-600 mt-2 font-medium">
        <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span>
    </div>
</div>

<!-- Détails sources -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Via Dispatch -->
    <div class="card p-6 animate-fade-in" style="animation-delay: 0.3s">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center mb-5">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mr-3">
                <i class="fa-regular fa-paper-plane text-emerald-600 text-xs"></i>
            </div>
            Via Dispatch
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-800">
                <span class="text-sm text-gray-600 dark:text-gray-300">Montant dispatché</span>
                <span class="text-sm font-bold text-emerald-600" id="stat-via-dispatch"><?= number_format($stats['besoins_satisfaits']['via_dispatch'], 0, ',', ' ') ?> Ar</span>
            </div>
            <div class="flex justify-between items-center p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                <span class="text-sm text-gray-600 dark:text-gray-300">Dons nature/matériels</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white" id="stat-dons-nature"><?= $stats['dons']['nature_materiel_qte'] ?> unités</span>
            </div>
        </div>
    </div>

    <!-- Via Achats -->
    <div class="card p-6 animate-fade-in" style="animation-delay: 0.35s">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center mb-5">
            <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center mr-3">
                <i class="fa-regular fa-credit-card text-purple-600 text-xs"></i>
            </div>
            Via Achats
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center p-4 rounded-lg bg-purple-50 dark:bg-purple-500/5 border border-purple-200 dark:border-purple-800">
                <span class="text-sm text-gray-600 dark:text-gray-300">Montant HT acheté</span>
                <span class="text-sm font-bold text-purple-600" id="stat-via-achats"><?= number_format($stats['besoins_satisfaits']['via_achats'], 0, ',', ' ') ?> Ar</span>
            </div>
            <div class="flex justify-between items-center p-4 rounded-lg bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-800">
                <span class="text-sm text-gray-600 dark:text-gray-300">Total avec frais</span>
                <span class="text-sm font-bold text-amber-600" id="stat-achats-total"><?= number_format($stats['achats']['montant_total'], 0, ',', ' ') ?> Ar</span>
            </div>
            <div class="flex justify-between items-center p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                <span class="text-sm text-gray-600 dark:text-gray-300">Quantité achetée</span>
                <span class="text-sm font-bold text-gray-900 dark:text-white" id="stat-achats-qte"><?= $stats['achats']['quantite'] ?> unités</span>
            </div>
        </div>
    </div>
</div>

<!-- Tableau par ville -->
<div class="card overflow-hidden animate-fade-in" style="animation-delay: 0.4s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                <i class="fa-regular fa-building text-brand-600 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Situation par ville</h3>
                <p class="text-xs text-gray-400">Détail par localité</p>
            </div>
        </div>
        <span class="badge bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400"><?= count($stats['par_ville']) ?> ville(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left">Ville</th>
                    <th class="text-left">Région</th>
                    <th class="text-right">Besoins</th>
                    <th class="text-right">Satisfaits</th>
                    <th class="text-right">Restants</th>
                    <th class="text-left">Progression</th>
                </tr>
            </thead>
            <tbody id="table-par-ville">
                <?php foreach ($stats['par_ville'] as $v): ?>
                    <?php 
                        $pct = $v['besoins_totaux'] > 0 ? round(($v['besoins_satisfaits'] / $v['besoins_totaux']) * 100, 1) : 0;
                        $fillClass = $pct >= 75 ? 'fill-green' : ($pct >= 50 ? 'fill-amber' : 'fill-red');
                        $textColor = $pct >= 75 ? 'text-emerald-600' : ($pct >= 50 ? 'text-amber-600' : 'text-red-500');
                    ?>
                    <tr>
                        <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($v['ville']) ?></td>
                        <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($v['region']) ?></span></td>
                        <td class="text-right text-gray-600 dark:text-gray-300 font-medium"><?= number_format($v['besoins_totaux'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-right font-bold text-emerald-600"><?= number_format($v['besoins_satisfaits'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-right font-bold text-red-500"><?= number_format($v['besoins_restants'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="flex-1 w-16 progress-bar">
                                    <div class="fill <?= $fillClass ?>" style="width: <?= min($pct, 100) ?>%"></div>
                                </div>
                                <span class="text-xs font-bold <?= $textColor ?> w-9 text-right"><?= $pct ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function actualiserStats() {
    const btnRefresh = document.getElementById('btn-refresh');
    const refreshIcon = document.getElementById('refresh-icon');
    btnRefresh.disabled = true;
    refreshIcon.classList.add('fa-spin');
    
    fetch('/api/recap')
        .then(r => r.json())
        .then(data => {
            document.getElementById('stat-besoins-totaux').textContent = formatNumber(data.besoins_totaux.montant);
            document.getElementById('stat-nb-besoins').textContent = data.besoins_totaux.nombre + ' besoins';
            document.getElementById('stat-besoins-satisfaits').textContent = formatNumber(data.besoins_satisfaits.montant);
            document.getElementById('stat-pourcentage').textContent = data.pourcentage_couverture + '%';
            document.getElementById('stat-besoins-restants').textContent = formatNumber(data.besoins_restants.montant);
            document.getElementById('stat-qte-restante').textContent = data.besoins_restants.quantite + ' unités';
            document.getElementById('stat-argent-restant').textContent = formatNumber(data.dons.argent_restant);
            document.getElementById('progress-percentage').textContent = data.pourcentage_couverture + '%';
            document.getElementById('progress-bar').style.width = Math.min(data.pourcentage_couverture, 100) + '%';
            document.getElementById('stat-via-dispatch').textContent = formatNumber(data.besoins_satisfaits.via_dispatch) + ' Ar';
            document.getElementById('stat-dons-nature').textContent = data.dons.nature_materiel_qte + ' unités';
            document.getElementById('stat-via-achats').textContent = formatNumber(data.besoins_satisfaits.via_achats) + ' Ar';
            document.getElementById('stat-achats-total').textContent = formatNumber(data.achats.montant_total) + ' Ar';
            document.getElementById('stat-achats-qte').textContent = data.achats.quantite + ' unités';
            document.getElementById('last-update').innerHTML = '<i class="fa-regular fa-clock mr-1"></i> Dernière mise à jour : ' + data.timestamp;
        })
        .catch(err => {
            console.error('Erreur:', err);
            alert('Erreur lors de l\'actualisation');
        })
        .finally(() => {
            btnRefresh.disabled = false;
            refreshIcon.classList.remove('fa-spin');
        });
}
function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
