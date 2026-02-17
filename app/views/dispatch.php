<?php
$page_title = 'Dispatch des dons';
$active = 'dispatch';
include __DIR__ . '/layout/header.php';
?>

<!-- Message -->
<?php if (!empty($message)): ?>
    <div class="mb-6 animate-fade-in">
        <div class="alert alert-info">
            <i class="fa-regular fa-circle-check mr-3 text-brand-500"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
<?php endif; ?>

<!-- Section d'action -->
<div class="mb-6 card bg-brand-800 dark:bg-brand-950 p-6 animate-fade-in">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-white">
            <h2 class="text-lg font-bold mb-1 flex items-center">
                <i class="fa-regular fa-paper-plane mr-3"></i>Distribution des dons
            </h2>
            <p class="text-blue-200/70 text-sm">
                <strong>Simulez</strong> pour voir le résultat, puis <strong>Validez</strong> pour confirmer.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="/dispatch/simuler" class="btn bg-white hover:bg-gray-50 text-brand-800 text-sm py-2.5 px-5">
                <i class="fa-regular fa-eye mr-2"></i>Simuler
            </a>
            <a href="/dispatch/valider" class="btn btn-success text-sm py-2.5 px-5">
                <i class="fa-regular fa-circle-check mr-2"></i>Valider
            </a>
        </div>
    </div>
</div>

<!-- Simulation -->
<?php if (!empty($simulation)): ?>
<div class="mb-6 card overflow-hidden border-2 !border-amber-300 dark:!border-amber-600 animate-fade-in">
    <div class="px-6 py-4 bg-amber-600 flex items-center justify-between">
        <h3 class="font-bold text-white flex items-center text-sm">
            <i class="fa-regular fa-eye mr-2"></i>Prévisualisation - <?= count($simulation) ?> attribution(s)
        </h3>
        <span class="text-xs font-semibold px-3 py-1 rounded-md bg-white/20 text-white">Non enregistré</span>
    </div>
    <div class="p-3 bg-amber-50 dark:bg-amber-500/5 border-b border-amber-200 dark:border-amber-700">
        <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
            <i class="fa-regular fa-circle-exclamation mr-1"></i>Ces attributions ne sont pas encore enregistrées. Cliquez sur "Valider" pour confirmer.
        </p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left !bg-amber-600">#</th>
                    <th class="text-left !bg-amber-600">Don</th>
                    <th class="text-left !bg-amber-600">Besoin</th>
                    <th class="text-left !bg-amber-600">Ville</th>
                    <th class="text-center !bg-amber-600">Qté</th>
                    <th class="text-right !bg-amber-600">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($simulation as $i => $s): ?>
                    <tr>
                        <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                        <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($s['don_designation']) ?></td>
                        <td class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($s['besoin_description']) ?></td>
                        <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($s['ville']) ?></span></td>
                        <td class="text-center"><span class="badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold"><?= $s['quantite_attribuee'] ?></span></td>
                        <td class="text-right font-bold text-gray-900 dark:text-white"><?= number_format($s['montant_attribue'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-amber-50 dark:bg-amber-500/5">
                    <td colspan="4" class="text-right font-bold text-amber-700 dark:text-amber-400 text-sm !py-4">Total :</td>
                    <td class="text-center font-bold text-amber-700 dark:text-amber-400"><?= array_sum(array_column($simulation, 'quantite_attribuee')) ?></td>
                    <td class="text-right font-bold text-amber-700 dark:text-amber-400"><?= number_format(array_sum(array_column($simulation, 'montant_attribue')), 0, ',', ' ') ?> Ar</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="stat-card animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total dispatches</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= count($dispatches ?? []) ?></p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                <i class="fa-regular fa-folder-open text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay: 0.15s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Qté distribuée</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= array_sum(array_column($dispatches ?? [], 'quantite_attribuee')) ?></p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                <i class="fa-regular fa-square-check text-brand-500 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Montant total</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= number_format(array_sum(array_column($dispatches ?? [], 'montant_attribue')), 0, ',', ' ') ?> Ar</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                <i class="fa-regular fa-money-bill-1 text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Historique -->
<div class="card overflow-hidden animate-fade-in" style="animation-delay: 0.25s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                <i class="fa-regular fa-clock text-purple-600 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Historique des dispatches</h3>
                <p class="text-xs text-gray-400">Distributions validées</p>
            </div>
        </div>
        <span class="badge bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400"><?= count($dispatches ?? []) ?> dispatch(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Don</th>
                    <th class="text-left">Besoin</th>
                    <th class="text-left">Ville</th>
                    <th class="text-center">Qté</th>
                    <th class="text-right">Montant</th>
                    <th class="text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dispatches)): ?>
                    <tr><td colspan="7" class="empty-state text-center">
                        <div class="empty-icon"><i class="fa-regular fa-folder-open text-gray-400 text-xl"></i></div>
                        <p class="text-sm font-semibold text-gray-400">Aucun dispatch effectué</p>
                        <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">Cliquez sur "Simuler" pour commencer</p>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($dispatches as $i => $di): ?>
                        <tr>
                            <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                            <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($di['don_designation']) ?></td>
                            <td class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($di['besoin_description']) ?></td>
                            <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($di['ville']) ?></span></td>
                            <td class="text-center"><span class="badge bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold"><?= $di['quantite_attribuee'] ?></span></td>
                            <td class="text-right font-bold text-gray-900 dark:text-white"><?= number_format($di['montant_attribue'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-gray-400 text-sm"><?= date('d/m/Y H:i', strtotime($di['date_dispatch'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
