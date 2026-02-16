<?php
$page_title = 'Dispatch des dons';
$active = 'dispatch';
include __DIR__ . '/layout/header.php';
?>

<!-- Message de succès après simulation -->
<?php if (!empty($message)): ?>
    <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 flex items-center shadow-sm">
        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center mr-4 shadow-lg shadow-green-500/30">
            <i class="fa-solid fa-circle-check text-lg"></i>
        </div>
        <div>
            <p class="font-semibold">Simulation réussie !</p>
            <p class="text-sm text-green-600"><?= htmlspecialchars($message) ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- Section d'action -->
<div class="mb-8 bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 rounded-2xl shadow-xl shadow-purple-500/20 p-6">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-white">
            <h2 class="text-xl font-bold mb-2 flex items-center">
                <i class="fa-solid fa-truck-fast mr-3"></i>
                Simulation de dispatch
            </h2>
            <p class="text-purple-100 text-sm max-w-xl">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Le dispatch attribue automatiquement les dons aux besoins par ordre chronologique de saisie.
                Les dons les plus anciens seront distribués en priorité.
            </p>
        </div>
        <a href="/dispatch/simuler"
           class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-purple-700 text-sm font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 group">
            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 group-hover:bg-purple-200 transition">
                <i class="fa-solid fa-play text-purple-600"></i>
            </div>
            Lancer la simulation
        </a>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total dispatches</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= count($dispatches ?? []) ?></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                <i class="fa-solid fa-boxes-stacked text-white text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité distribuée</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    <?= array_sum(array_column($dispatches ?? [], 'quantite_attribuee')) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-cubes text-white text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Montant total</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    <?= number_format(array_sum(array_column($dispatches ?? [], 'montant_attribue')), 0, ',', ' ') ?> <span class="text-sm text-gray-500">Ar</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/30">
                <i class="fa-solid fa-coins text-white text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Historique des dispatches -->
<div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 overflow-hidden border border-gray-100">
    <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-purple-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center mr-3 shadow-lg shadow-purple-500/30">
                <i class="fa-solid fa-clock-rotate-left text-white"></i>
            </div>
            Historique des dispatches
        </h3>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700 ring-2 ring-purple-200">
            <i class="fa-solid fa-list mr-1.5"></i>
            <?= count($dispatches ?? []) ?> dispatch(s)
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Don</th>
                    <th class="px-6 py-4">Besoin</th>
                    <th class="px-6 py-4">Ville</th>
                    <th class="px-6 py-4 text-center">Qté attribuée</th>
                    <th class="px-6 py-4 text-right">Montant</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($dispatches)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-inbox text-gray-300 text-4xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucun dispatch effectué</p>
                            <p class="text-gray-400 text-sm mt-1">Cliquez sur "Lancer la simulation" pour commencer la distribution</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dispatches as $i => $di): ?>
                        <tr class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-indigo-50/50 transition-all duration-200 group">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 text-sm font-bold group-hover:bg-purple-100 group-hover:text-purple-700 transition">
                                    <?= $i + 1 ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center mr-3 shadow-sm">
                                        <i class="fa-solid fa-gift text-white text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-gray-800"><?= htmlspecialchars($di['don_designation']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mr-3 shadow-sm">
                                        <i class="fa-solid fa-clipboard-list text-white text-sm"></i>
                                    </div>
                                    <span class="text-gray-700"><?= htmlspecialchars($di['besoin_description']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 ring-1 ring-blue-200">
                                    <i class="fa-solid fa-location-dot mr-1.5"></i>
                                    <?= htmlspecialchars($di['ville']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[60px] px-3 py-1.5 rounded-lg bg-purple-100 text-purple-700 font-bold text-sm">
                                    <?= $di['quantite_attribuee'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-800"><?= number_format($di['montant_attribue'], 0, ',', ' ') ?></span>
                                <span class="text-gray-500 text-sm ml-1">Ar</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-2">
                                        <i class="fa-regular fa-calendar text-gray-400"></i>
                                    </div>
                                    <?= date('d/m/Y H:i', strtotime($di['date_dispatch'])) ?>
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
