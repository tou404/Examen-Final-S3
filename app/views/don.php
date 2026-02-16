<?php
$page_title = 'Gestion des dons';
$active = 'don';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    <!-- Formulaire ajout don -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-gift text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Saisir un don</h3>
                        <p class="text-xs text-slate-500">Nouveau don reçu</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="/don" class="space-y-4">
                    
                    <!-- Section Donateur -->
                    <div class="pb-4 border-b border-slate-100">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500 font-bold mb-4 flex items-center">
                            <i class="fa-solid fa-user text-emerald-500 mr-2"></i> Donateur
                        </p>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1">Nom</label>
                                <input type="text" name="nom" id="nom" required
                                       class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            </div>
                            <div>
                                <label for="prenom" class="block text-sm font-semibold text-slate-700 mb-1">Prénom</label>
                                <input type="text" name="prenom" id="prenom" required
                                       class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">
                                <i class="fa-solid fa-envelope text-emerald-500 mr-1"></i> Email
                            </label>
                            <input type="email" name="email" id="email" required
                                   class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                        </div>
                        <div>
                            <label for="telephone" class="block text-sm font-semibold text-slate-700 mb-1">
                                <i class="fa-solid fa-phone text-emerald-500 mr-1"></i> Téléphone
                            </label>
                            <input type="text" name="telephone" id="telephone"
                                   class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                        </div>
                    </div>

                    <!-- Section Don -->
                    <div class="pt-2">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500 font-bold mb-4 flex items-center">
                            <i class="fa-solid fa-heart text-emerald-500 mr-2"></i> Détails du don
                        </p>
                        <div class="space-y-3">
                            <div>
                                <label for="type_besoin_id" class="block text-sm font-semibold text-slate-700 mb-1">
                                    <i class="fa-solid fa-tags text-emerald-500 mr-1"></i> Type
                                </label>
                                <select name="type_besoin_id" id="type_besoin_id" required
                                        class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($types as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="designation" class="block text-sm font-semibold text-slate-700 mb-1">
                                    <i class="fa-solid fa-align-left text-emerald-500 mr-1"></i> Désignation
                                </label>
                                <input type="text" name="designation" id="designation"
                                       placeholder="Ex: Sac de riz 50kg"
                                       class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="quantite" class="block text-sm font-semibold text-slate-700 mb-1">
                                        <i class="fa-solid fa-hashtag text-emerald-500 mr-1"></i> Quantité
                                    </label>
                                    <input type="number" min="0" name="quantite" id="quantite"
                                           placeholder="0"
                                           class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                                </div>
                                <div>
                                    <label for="montant" class="block text-sm font-semibold text-slate-700 mb-1">
                                        <i class="fa-solid fa-coins text-emerald-500 mr-1"></i> Montant
                                    </label>
                                    <input type="number" step="0.01" min="0" name="montant" id="montant"
                                           placeholder="0"
                                           class="w-full rounded-xl border-slate-200 border-2 px-4 py-2.5 text-sm focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50 hover:bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 flex items-center justify-center mt-4">
                        <i class="fa-solid fa-heart mr-2"></i>
                        Enregistrer le don
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des dons -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-list text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Liste des dons</h3>
                        <p class="text-xs text-slate-500">Tous les dons reçus</p>
                    </div>
                </div>
                <span class="text-xs bg-gradient-to-r from-emerald-500 to-green-600 text-white px-3 py-1.5 rounded-full font-semibold shadow-lg shadow-emerald-500/30">
                    <?= count($dons) ?> don(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-pro">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Donateur</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Désignation</th>
                            <th class="px-6 py-4 text-right">Quantité</th>
                            <th class="px-6 py-4 text-right">Montant</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($dons)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-gift text-slate-300 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aucun don enregistré</p>
                                        <p class="text-slate-400 text-sm mt-1">Commencez par saisir un don</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dons as $i => $d): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-user text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($d['donateur'] ?? 'Anonyme') ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700">
                                            <?= htmlspecialchars($d['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($d['designation'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-slate-800"><?= $d['quantite'] ?? '-' ?></td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-emerald-600">
                                        <?= $d['montant'] ? number_format($d['montant'], 0, ',', ' ') . ' Ar' : '-' ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        <i class="fa-regular fa-calendar mr-1 text-slate-400"></i>
                                        <?= isset($d['date_don']) ? date('d/m/Y', strtotime($d['date_don'])) : '-' ?>
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
