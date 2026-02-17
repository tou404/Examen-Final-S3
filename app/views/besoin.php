<?php
$page_title = 'Gestion des besoins';
$active = 'besoin';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Formulaire -->
    <div class="xl:col-span-1 animate-fade-in">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                        <i class="fa-regular fa-square-plus text-amber-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Ajouter un besoin</h3>
                        <p class="text-xs text-gray-400">Nouveau besoin pour une ville</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="/besoin" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Ville <span class="text-red-400">*</span></label>
                        <select name="ville_id" required class="w-full input">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['ville']) ?> (<?= htmlspecialchars($v['region']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Type <span class="text-red-400">*</span></label>
                        <select name="type_besoin_id" required class="w-full input">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Description <span class="text-red-400">*</span></label>
                        <input type="text" name="description" required placeholder="Ex: Sacs de riz 50kg" class="w-full input">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Prix unit. (Ar) <span class="text-red-400">*</span></label>
                            <input type="number" step="0.01" min="0" name="prix_unitaire" required class="w-full input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité <span class="text-red-400">*</span></label>
                            <input type="number" min="1" name="quantite" required class="w-full input">
                        </div>
                    </div>
                    <button type="submit" class="w-full btn btn-warning py-3 text-sm">
                        <i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste -->
    <div class="xl:col-span-2 animate-fade-in-delayed">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                        <i class="fa-regular fa-clipboard text-amber-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Liste des besoins</h3>
                        <p class="text-xs text-gray-400">Tous les besoins enregistrés</p>
                    </div>
                </div>
                <span class="badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400"><?= count($besoins) ?> besoin(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tbl">
                    <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">Ville</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Description</th>
                            <th class="text-right">Prix unit.</th>
                            <th class="text-right">Qté</th>
                            <th class="text-right">Valeur</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($besoins)): ?>
                            <tr><td colspan="8" class="empty-state text-center">
                                <div class="empty-icon"><i class="fa-regular fa-clipboard text-gray-400 text-xl"></i></div>
                                <p class="text-sm font-semibold text-gray-400">Aucun besoin enregistré</p>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($besoins as $i => $b): ?>
                                <tr>
                                    <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                                    <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($b['ville']) ?></td>
                                    <td><span class="badge bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400"><?= htmlspecialchars($b['type']) ?></span></td>
                                    <td class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($b['description'] ?? '-') ?></td>
                                    <td class="text-right text-gray-600 dark:text-gray-300 font-medium"><?= number_format($b['prix_unitaire'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-right font-bold text-gray-900 dark:text-white"><?= $b['quantite'] ?></td>
                                    <td class="text-right font-bold text-amber-600"><?= number_format(($b['prix_unitaire'] ?? 0) * ($b['quantite'] ?? 0), 0, ',', ' ') ?> Ar</td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button onclick="openEditBesoinModal(<?= $b['id'] ?>, <?= $b['ville_id'] ?>, <?= $b['type_besoin_id'] ?>, '<?= htmlspecialchars($b['description'] ?? '', ENT_QUOTES) ?>', <?= $b['prix_unitaire'] ?>, <?= $b['quantite'] ?>)"
                                                    class="act-btn act-btn-edit" data-tip="Modifier"><i class="fa-regular fa-pen-to-square text-xs"></i></button>
                                            <button onclick="confirmDeleteBesoin(<?= $b['id'] ?>, '<?= htmlspecialchars($b['description'] ?? '', ENT_QUOTES) ?>')"
                                                    class="act-btn act-btn-delete" data-tip="Supprimer"><i class="fa-regular fa-trash-can text-xs"></i></button>
                                        </div>
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

<!-- Modal Edit -->
<div id="editBesoinModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-md mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-amber-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm"><i class="fa-regular fa-pen-to-square mr-2"></i>Modifier le besoin</h3>
            <button onclick="closeEditBesoinModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition"><i class="fa-solid fa-xmark text-sm"></i></button>
        </div>
        <form method="POST" action="/besoin/update" class="p-6 space-y-4">
            <input type="hidden" name="id" id="edit_besoin_id">
            <input type="hidden" name="ville_id" id="edit_besoin_ville_id">
            <input type="hidden" name="type_besoin_id" id="edit_besoin_type_id">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Description</label>
                <input type="text" name="description" id="edit_besoin_description" required class="w-full input">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Prix unitaire</label>
                    <input type="number" step="0.01" min="0" name="prix_unitaire" id="edit_besoin_prix" required class="w-full input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité</label>
                    <input type="number" min="1" name="quantite" id="edit_besoin_quantite" required class="w-full input">
                </div>
            </div>
            <div class="flex space-x-3 pt-2">
                <button type="button" onclick="closeEditBesoinModal()" class="flex-1 btn btn-secondary py-3 text-sm">Annuler</button>
                <button type="submit" class="flex-1 btn btn-warning py-3 text-sm"><i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteBesoinModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-sm mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-red-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm"><i class="fa-regular fa-circle-exclamation mr-2"></i>Confirmer la suppression</h3>
            <button onclick="closeDeleteBesoinModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition"><i class="fa-solid fa-xmark text-sm"></i></button>
        </div>
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center mx-auto mb-4"><i class="fa-regular fa-trash-can text-red-500 text-xl"></i></div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Êtes-vous sûr de vouloir supprimer</p>
            <p class="text-base font-bold text-gray-900 dark:text-white mt-1 mb-6" id="delete_besoin_name"></p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeDeleteBesoinModal()" class="flex-1 btn btn-secondary py-3 text-sm">Annuler</button>
                <a id="deleteBesoinLink" href="#" class="flex-1 btn btn-danger py-3 text-sm"><i class="fa-regular fa-trash-can mr-2"></i>Supprimer</a>
            </div>
        </div>
    </div>
</div>

<script>
function openEditBesoinModal(id, villeId, typeId, description, prix, quantite) {
    document.getElementById('edit_besoin_id').value = id;
    document.getElementById('edit_besoin_ville_id').value = villeId;
    document.getElementById('edit_besoin_type_id').value = typeId;
    document.getElementById('edit_besoin_description').value = description;
    document.getElementById('edit_besoin_prix').value = prix;
    document.getElementById('edit_besoin_quantite').value = quantite;
    document.getElementById('editBesoinModal').classList.remove('hidden');
    document.getElementById('editBesoinModal').classList.add('flex');
}
function closeEditBesoinModal() { document.getElementById('editBesoinModal').classList.add('hidden'); document.getElementById('editBesoinModal').classList.remove('flex'); }
function confirmDeleteBesoin(id, nom) {
    document.getElementById('delete_besoin_name').textContent = nom + ' ?';
    document.getElementById('deleteBesoinLink').href = '/besoin/delete/' + id;
    document.getElementById('deleteBesoinModal').classList.remove('hidden');
    document.getElementById('deleteBesoinModal').classList.add('flex');
}
function closeDeleteBesoinModal() { document.getElementById('deleteBesoinModal').classList.add('hidden'); document.getElementById('deleteBesoinModal').classList.remove('flex'); }
document.getElementById('editBesoinModal').addEventListener('click', function(e) { if (e.target === this) closeEditBesoinModal(); });
document.getElementById('deleteBesoinModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteBesoinModal(); });
</script>
