<?php
$page_title = 'Gestion des villes';
$active = 'ville';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Formulaire ajout ville -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-plus-circle mr-2 text-blue-600"></i>
                    Ajouter une ville
                </h3>
            </div>
            <div class="p-6">
                <form method="post" action="/ville">
                    <div class="mb-4">
                        <label for="region_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-map mr-1 text-gray-400"></i> Région
                        </label>
                        <select name="region_id" id="region_id" required
                                class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($regions as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fa-solid fa-city mr-1 text-gray-400"></i> Nom de la ville
                        </label>
                        <input type="text" name="nom" id="nom" required
                               placeholder="Ex: Antananarivo"
                               class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des villes -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">
                    <i class="fa-solid fa-list mr-2 text-blue-600"></i>
                    Liste des villes
                </h3>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                    <?= count($villes) ?> ville(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Région</th>
                            <th class="px-6 py-3">Ville</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($villes)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                    Aucune ville enregistrée
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($villes as $i => $v): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= $i + 1 ?></td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fa-solid fa-map-pin mr-1"></i>
                                            <?= htmlspecialchars($v['region']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-800">
                                        <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i>
                                        <?= htmlspecialchars($v['ville']) ?>
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
