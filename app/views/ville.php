<?php
$page_title = 'Gestion des villes';
$active = 'ville';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    <!-- Formulaire ajout ville -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-cyan-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/30">
                        <i class="fa-solid fa-plus text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Ajouter une ville</h3>
                        <p class="text-xs text-slate-500">Nouvelle localité</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="/ville" class="space-y-5">
                    <div>
                        <label for="region_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-map text-cyan-500 mr-1"></i> Région
                        </label>
                        <select name="region_id" id="region_id" required
                                class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 outline-none transition-all bg-slate-50 hover:bg-white">
                            <option value="">-- Sélectionner une région --</option>
                            <?php foreach ($regions as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-city text-cyan-500 mr-1"></i> Nom de la ville
                        </label>
                        <input type="text" name="nom" id="nom" required
                               placeholder="Ex: Antananarivo"
                               class="w-full rounded-xl border-slate-200 border-2 px-4 py-3 text-sm focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 outline-none transition-all bg-slate-50 hover:bg-white">
                    </div>
                    <button type="submit"
                            class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-xl transition-all flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des villes -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-list text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Liste des villes</h3>
                        <p class="text-xs text-slate-500">Toutes les localités enregistrées</p>
                    </div>
                </div>
                <span class="text-xs bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-3 py-1.5 rounded-full font-semibold shadow-lg shadow-cyan-500/30">
                    <?= count($villes) ?> ville(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-pro">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Région</th>
                            <th class="px-6 py-4">Ville</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($villes)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-city text-slate-300 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aucune ville enregistrée</p>
                                        <p class="text-slate-400 text-sm mt-1">Commencez par ajouter une ville</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($villes as $i => $v): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                            <i class="fa-solid fa-map-pin mr-1.5 text-blue-400"></i>
                                            <?= htmlspecialchars($v['region']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-800 flex items-center">
                                            <i class="fa-solid fa-location-dot mr-2 text-cyan-500"></i>
                                            <?= htmlspecialchars($v['ville']) ?>
                                        </span>
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
