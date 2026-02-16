<?php
$page_title = 'Dispatch des dons';
$active = 'dispatch';
include __DIR__ . '/layout/header.php';
?>

<!-- Message de succès après simulation -->
<?php if (!empty($message)): ?>
    <div class="mb-5 p-4 rounded-lg bg-green-100 border border-green-300 text-green-800 flex items-center">
        <i class="fa-solid fa-circle-check text-xl mr-3"></i>
        <span class="text-sm font-medium"><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<!-- Bouton simuler le dispatch -->
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Le dispatch attribue automatiquement les dons aux besoins par ordre chronologique de saisie.
    </p>
    <a href="/dispatch/simuler"
       class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
        <i class="fa-solid fa-play mr-2"></i>
        Lancer la simulation
    </a>
</div>

<!-- Historique des dispatches -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-800">
            <i class="fa-solid fa-truck-fast mr-2 text-purple-600"></i>
            Historique des dispatches
        </h3>
        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-medium">
            <?= count($dispatches) ?> dispatch(s)
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Don</th>
                    <th class="px-6 py-3">Besoin</th>
                    <th class="px-6 py-3">Ville</th>
                    <th class="px-6 py-3 text-right">Qté attribuée</th>
                    <th class="px-6 py-3 text-right">Montant</th>
                    <th class="px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($dispatches)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            Aucun dispatch effectué.<br>
                            <span class="text-xs">Cliquez sur "Lancer la simulation" pour commencer.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dispatches as $i => $di): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-800">
                                <i class="fa-solid fa-gift mr-1 text-green-500"></i>
                                <?= htmlspecialchars($di['don_designation']) ?>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-700">
                                <i class="fa-solid fa-clipboard-list mr-1 text-amber-500"></i>
                                <?= htmlspecialchars($di['besoin_description']) ?>
                            </td>
                            <td class="px-6 py-3 text-sm">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    <?= htmlspecialchars($di['ville']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-right text-gray-700"><?= $di['quantite_attribuee'] ?></td>
                            <td class="px-6 py-3 text-sm text-right font-medium text-gray-800">
                                <?= number_format($di['montant_attribue'], 0, ',', ' ') ?> Ar
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-500">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                <?= date('d/m/Y H:i', strtotime($di['date_dispatch'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
