<?php
$page_title = 'Distribution aux bénéficiaires';
$active = 'distribution';
include __DIR__ . '/layout/header.php';
?>

<!-- Messages -->
<?php if (!empty($message)): ?>
    <div class="mb-6 animate-fade-in">
        <div class="alert alert-success">
            <i class="fa-regular fa-circle-check mr-3"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mb-6 animate-fade-in">
        <div class="alert alert-error">
            <i class="fa-regular fa-circle-xmark mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="stat-card animate-fade-in" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total distributions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= intval($stats['total_distributions'] ?? 0) ?></p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                <i class="fa-regular fa-truck-fast text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Qté distribuée</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= number_format(intval($stats['total_quantite'] ?? 0), 0, ',', ' ') ?></p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fa-regular fa-boxes-stacked text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Bénéficiaires</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= number_format(intval($stats['total_beneficiaires'] ?? 0), 0, ',', ' ') ?></p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                <i class="fa-regular fa-users text-brand-500 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Formulaire de distribution -->
    <div class="card animate-fade-in" style="animation-delay: 0.2s">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                    <i class="fa-regular fa-hand-holding-heart text-indigo-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Nouvelle distribution</h3>
                    <p class="text-xs text-gray-400">Distribuer aux bénéficiaires</p>
                </div>
            </div>
        </div>
        <form method="POST" action="/distribution" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Besoin à distribuer <span class="text-red-400">*</span></label>
                <select name="besoin_id" id="besoin_select" required class="w-full input" onchange="updateVille()">
                    <option value="">Sélectionner...</option>
                    <?php foreach ($besoinsDisponibles as $b): ?>
                        <option value="<?= $b['id'] ?>" data-ville-id="<?= $b['ville_id'] ?>" data-dispo="<?= $b['quantite_disponible'] ?>" data-ville="<?= htmlspecialchars($b['ville']) ?>">
                            <?= htmlspecialchars($b['ville']) ?> — <?= htmlspecialchars($b['description']) ?> (<?= $b['quantite_disponible'] ?> dispo)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="ville_id" id="ville_id_hidden" value="">

            <div id="info_dispo" class="hidden rounded-lg bg-blue-50 dark:bg-blue-500/5 border border-blue-200 dark:border-blue-800 p-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Ville :</span>
                    <span id="info_ville" class="font-bold text-brand-600 dark:text-brand-400"></span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-gray-500 dark:text-gray-400">Disponible :</span>
                    <span id="info_qte" class="font-bold text-emerald-600"></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité à distribuer <span class="text-red-400">*</span></label>
                <input type="number" name="quantite_distribuee" id="quantite_distribuee" min="1" required class="w-full input" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Lieu de distribution</label>
                <input type="text" name="lieu_distribution" class="w-full input" placeholder="Ex: Centre communal, École...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Nombre de bénéficiaires</label>
                <input type="number" name="beneficiaires" min="0" class="w-full input" placeholder="0">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Observations</label>
                <textarea name="observations" rows="2" class="w-full input" placeholder="Notes..."></textarea>
            </div>

            <button type="submit" class="w-full btn btn-primary py-3 text-sm">
                <i class="fa-regular fa-hand-holding-heart mr-2"></i>Enregistrer la distribution
            </button>
        </form>
    </div>

    <!-- Distribution par ville -->
    <div class="lg:col-span-2 card animate-fade-in" style="animation-delay: 0.25s">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                    <i class="fa-regular fa-chart-bar text-amber-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Distribution par ville</h3>
                    <p class="text-xs text-gray-400">Résumé par localité</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <?php if (empty($stats['par_ville'])): ?>
                <div class="empty-state text-center py-10">
                    <div class="empty-icon"><i class="fa-regular fa-chart-bar text-gray-400 text-xl"></i></div>
                    <p class="text-sm font-semibold text-gray-400">Aucune distribution enregistrée</p>
                    <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">Commencez par distribuer des articles</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php
                    $maxQte = max(array_column($stats['par_ville'], 'quantite_distribuee'));
                    foreach ($stats['par_ville'] as $pv):
                        $pct = $maxQte > 0 ? ($pv['quantite_distribuee'] / $maxQte) * 100 : 0;
                    ?>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($pv['ville']) ?></span>
                                    <span class="text-xs text-gray-400"><?= $pv['nb_distributions'] ?> distribution(s)</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-400"><i class="fa-regular fa-users mr-1"></i><?= number_format(intval($pv['total_beneficiaires']), 0, ',', ' ') ?></span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white"><?= number_format(intval($pv['quantite_distribuee']), 0, ',', ' ') ?> unités</span>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="fill-blue" style="width: <?= round($pct) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Historique des distributions -->
<div class="card overflow-hidden animate-fade-in" style="animation-delay: 0.3s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                <i class="fa-regular fa-clock text-purple-600 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Historique des distributions</h3>
                <p class="text-xs text-gray-400">Toutes les distributions effectuées</p>
            </div>
        </div>
        <span class="badge bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400"><?= count($distributions) ?> distribution(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Besoin</th>
                    <th class="text-left">Type</th>
                    <th class="text-left">Ville</th>
                    <th class="text-center">Qté</th>
                    <th class="text-left">Lieu</th>
                    <th class="text-center">Bénéficiaires</th>
                    <th class="text-left">Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($distributions)): ?>
                    <tr><td colspan="9" class="empty-state text-center">
                        <div class="empty-icon"><i class="fa-regular fa-hand-holding-heart text-gray-400 text-xl"></i></div>
                        <p class="text-sm font-semibold text-gray-400">Aucune distribution effectuée</p>
                        <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">Remplissez le formulaire pour commencer</p>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($distributions as $i => $d): ?>
                        <tr>
                            <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                            <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($d['besoin_description']) ?></td>
                            <td><span class="badge bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400"><?= htmlspecialchars($d['type_besoin']) ?></span></td>
                            <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($d['ville']) ?></span></td>
                            <td class="text-center"><span class="badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold"><?= $d['quantite_distribuee'] ?></span></td>
                            <td class="text-gray-600 dark:text-gray-300 text-sm"><?= htmlspecialchars($d['lieu_distribution'] ?? '—') ?></td>
                            <td class="text-center">
                                <?php if ($d['beneficiaires']): ?>
                                    <span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400 font-bold"><?= number_format($d['beneficiaires'], 0, ',', ' ') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-gray-400 text-sm"><?= date('d/m/Y H:i', strtotime($d['date_distribution'])) ?></td>
                            <td class="text-center">
                                <a href="/distribution/delete/<?= $d['id'] ?>" 
                                   onclick="return confirm('Supprimer cette distribution ?')"
                                   class="act-btn text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10" data-tip="Supprimer">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updateVille() {
    var sel = document.getElementById('besoin_select');
    var opt = sel.options[sel.selectedIndex];
    var infoDispo = document.getElementById('info_dispo');
    var villeHidden = document.getElementById('ville_id_hidden');
    var qteInput = document.getElementById('quantite_distribuee');

    if (sel.value) {
        var villeId = opt.getAttribute('data-ville-id');
        var dispo = opt.getAttribute('data-dispo');
        var ville = opt.getAttribute('data-ville');

        villeHidden.value = villeId;
        document.getElementById('info_ville').textContent = ville;
        document.getElementById('info_qte').textContent = dispo + ' unité(s)';
        qteInput.max = dispo;
        infoDispo.classList.remove('hidden');
    } else {
        villeHidden.value = '';
        infoDispo.classList.add('hidden');
        qteInput.removeAttribute('max');
    }
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
