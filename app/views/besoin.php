<?php
$page_title = 'Gestion des besoins';
$active = 'besoin';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Formulaire ajout besoin -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-white">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-plus-circle mr-2 text-amber-600"></i>
                    Saisir un besoin
                </h3>
            </div>
            <div class="p-6">
                <form method="post" action="/besoin">
                    <div class="mb-4">
                        <label for="ville_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-city mr-1 text-gray-400"></i> Ville
                        </label>
                        <select name="ville_id" id="ville_id" required
                                class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['ville']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-tags mr-1 text-gray-400"></i> Type de besoin
                        </label>
                        <select name="type_besoin_id" id="type_besoin_id" required
                                class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?> (<?= htmlspecialchars($t['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-align-left mr-1 text-gray-400"></i> Description
                        </label>
                        <input type="text" name="description" id="description" required
                               placeholder="Ex: Riz, Tôle, ..."
                               class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div>
                            <label for="prix_unitaire" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-solid fa-coins mr-1 text-gray-400"></i> Prix unitaire
                            </label>
                            <input type="number" step="0.01" min="0" name="prix_unitaire" id="prix_unitaire" required
                                   placeholder="0.00"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                        </div>
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-solid fa-hashtag mr-1 text-gray-400"></i> Quantité
                            </label>
                            <input type="number" min="1" name="quantite" id="quantite" required
                                   placeholder="0"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des besoins -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-clipboard-list mr-2 text-amber-600"></i>
                    Liste des besoins
                </h3>
                <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full font-medium">
                    <?= count($besoins) ?> besoin(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Ville</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3 text-right">Prix unit.</th>
                            <th class="px-6 py-3 text-right">Quantité</th>
                            <th class="px-6 py-3 text-right">Qté restante</th>
                            <th class="px-6 py-3 text-right">Valeur totale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($besoins)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                    Aucun besoin enregistré
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($besoins as $i => $b): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-800"><?= htmlspecialchars($b['ville']) ?></td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <?= htmlspecialchars($b['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?= htmlspecialchars($b['description']) ?></td>
                                    <td class="px-6 py-3 text-sm text-right text-gray-700"><?= number_format($b['prix_unitaire'], 2, ',', ' ') ?></td>
                                    <td class="px-6 py-3 text-sm text-right text-gray-700"><?= $b['quantite'] ?></td>
                                    <td class="px-6 py-3 text-sm text-right <?= $b['quantite_restante'] > 0 ? 'text-red-600 font-medium' : 'text-green-600' ?>">
                                        <?= $b['quantite_restante'] ?>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-right font-medium text-gray-800">
                                        <?= number_format($b['prix_unitaire'] * $b['quantite'], 0, ',', ' ') ?> Ar
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
