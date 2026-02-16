<?php
$page_title = 'Gestion des besoins';
$active = 'besoin';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    <!-- Formulaire ajout besoin -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <i class="fa-solid fa-plus text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Saisir un besoin</h3>
                        <p class="text-xs text-slate-500">Nouveau besoin sinistré</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="/besoin" class="space-y-4">
                    <div>
                        <label for="ville_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-city text-amber-500 mr-1"></i> Ville
                        </label>
                        <select name="ville_id" id="ville_id" required
                                class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            <option value="">-- Sélectionner une ville --</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['ville']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="type_besoin_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-tags text-amber-500 mr-1"></i> Type de besoin
                        </label>
                        <select name="type_besoin_id" id="type_besoin_id" required
                                class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            <option value="">-- Sélectionner un type --</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-align-left text-amber-500 mr-1"></i> Description
                        </label>
                        <input type="text" name="description" id="description" required
                               placeholder="Ex: Riz, Tôle, Huile..."
                               class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all bg-slate-50 hover:bg-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="prix_unitaire" class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-coins text-amber-500 mr-1"></i> Prix unit.
                            </label>
                            <input type="number" step="0.01" min="0" name="prix_unitaire" id="prix_unitaire" required
                                   placeholder="0"
                                   class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all bg-slate-50 hover:bg-white">
                        </div>
                        <div>
                            <label for="quantite" class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fa-solid fa-hashtag text-amber-500 mr-1"></i> Quantité
                            </label>
                            <input type="number" min="1" name="quantite" id="quantite" required
                                   placeholder="0"
                                   class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all bg-slate-50 hover:bg-white">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des besoins -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-list text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Liste des besoins</h3>
                        <p class="text-xs text-slate-500">Besoins enregistrés par ville</p>
                    </div>
                </div>
                <span class="text-xs bg-gradient-to-r from-amber-500 to-orange-500 text-white px-3 py-1.5 rounded-full font-semibold shadow-lg shadow-amber-500/30">
                    <?= count($besoins) ?> besoin(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-pro">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Ville</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Prix unit.</th>
                            <th class="px-6 py-4 text-right">Quantité</th>
                            <th class="px-6 py-4 text-right">Valeur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($besoins)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-clipboard-list text-slate-300 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aucun besoin enregistré</p>
                                        <p class="text-slate-400 text-sm mt-1">Commencez par saisir un besoin</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($besoins as $i => $b): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-800"><?= htmlspecialchars($b['ville']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                            <?= htmlspecialchars($b['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($b['description'] ?? $b['designation'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-sm text-right text-slate-700"><?= number_format($b['prix_unitaire'], 0, ',', ' ') ?> <span class="text-slate-400">Ar</span></td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-slate-800"><?= $b['quantite'] ?? $b['quantite_demande'] ?></td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-amber-600">
                                        <?= number_format(($b['prix_unitaire'] ?? 0) * ($b['quantite'] ?? $b['quantite_demande'] ?? 0), 0, ',', ' ') ?> <span class="text-amber-400">Ar</span>
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
