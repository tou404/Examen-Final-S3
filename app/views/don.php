<?php
$page_title = 'Gestion des dons';
$active = 'don';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Formulaire ajout don -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-plus-circle mr-2 text-green-600"></i>
                    Saisir un don
                </h3>
            </div>
            <div class="p-6">
                <form method="post" action="/don">
                    <!-- Info donateur -->
                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-3">
                        <i class="fa-solid fa-user mr-1"></i> Donateur
                    </p>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" name="nom" id="nom" required
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input type="text" name="prenom" id="prenom" required
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-envelope mr-1 text-gray-400"></i> Email
                        </label>
                        <input type="email" name="email" id="email" required
                               class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                    </div>
                    <div class="mb-4">
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-phone mr-1 text-gray-400"></i> Téléphone
                        </label>
                        <input type="text" name="telephone" id="telephone"
                               class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                    </div>

                    <hr class="my-4">

                    <!-- Info don -->
                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-3">
                        <i class="fa-solid fa-gift mr-1"></i> Détails du don
                    </p>
                    <div class="mb-4">
                        <label for="type_besoin_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-tags mr-1 text-gray-400"></i> Type
                        </label>
                        <select name="type_besoin_id" id="type_besoin_id" required
                                class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-align-left mr-1 text-gray-400"></i> Désignation
                        </label>
                        <input type="text" name="designation" id="designation"
                               placeholder="Ex: Sac de riz 50kg"
                               class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-solid fa-hashtag mr-1 text-gray-400"></i> Quantité
                            </label>
                            <input type="number" min="0" name="quantite" id="quantite"
                                   placeholder="0"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                        </div>
                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-solid fa-coins mr-1 text-gray-400"></i> Montant (Ar)
                            </label>
                            <input type="number" step="0.01" min="0" name="montant" id="montant"
                                   placeholder="0.00"
                                   class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Enregistrer le don
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des dons -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-gift mr-2 text-green-600"></i>
                    Liste des dons
                </h3>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full font-medium">
                    <?= count($dons) ?> don(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Donateur</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Désignation</th>
                            <th class="px-6 py-3 text-right">Quantité</th>
                            <th class="px-6 py-3 text-right">Montant</th>
                            <th class="px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($dons)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                    Aucun don enregistré
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dons as $i => $d): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-800">
                                        <i class="fa-solid fa-user-circle mr-1 text-gray-400"></i>
                                        <?= htmlspecialchars($d['donateur'] ?? 'Anonyme') ?>
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?= htmlspecialchars($d['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?= htmlspecialchars($d['designation'] ?? '-') ?></td>
                                    <td class="px-6 py-3 text-sm text-right text-gray-700"><?= $d['quantite'] ?? '-' ?></td>
                                    <td class="px-6 py-3 text-sm text-right font-medium text-gray-800">
                                        <?= $d['montant'] ? number_format($d['montant'], 0, ',', ' ') . ' Ar' : '-' ?>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-500">
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        <?= date('d/m/Y', strtotime($d['date_don'])) ?>
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
