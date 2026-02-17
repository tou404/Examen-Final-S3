<?php
$page_title = 'Gestion des villes';
$active = 'ville';
include __DIR__ . '/layout/header.php';
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Formulaire -->
    <div class="xl:col-span-1 animate-fade-in">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center">
                        <i class="fa-regular fa-square-plus text-brand-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Ajouter une ville</h3>
                        <p class="text-xs text-gray-400">Nouvelle localité</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="/ville" class="space-y-5">
                    <div>
                        <label for="region_id" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                            Région <span class="text-red-400">*</span>
                        </label>
                        <select name="region_id" id="region_id" required class="w-full input">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($regions as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="nom" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                            Nom de la ville <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" required placeholder="Ex: Antananarivo" class="w-full input">
                    </div>
                    <button type="submit" class="w-full btn btn-primary py-3 text-sm">
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
                    <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                        <i class="fa-regular fa-rectangle-list text-brand-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Liste des villes</h3>
                        <p class="text-xs text-gray-400">Toutes les localités</p>
                    </div>
                </div>
                <span class="badge bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                    <?= count($villes) ?> ville(s)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tbl">
                    <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">Région</th>
                            <th class="text-left">Ville</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($villes)): ?>
                            <tr><td colspan="4" class="empty-state text-center">
                                <div class="empty-icon"><i class="fa-regular fa-building text-gray-400 text-xl"></i></div>
                                <p class="text-sm font-semibold text-gray-400">Aucune ville enregistrée</p>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($villes as $i => $v): ?>
                                <tr>
                                    <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                                    <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($v['region']) ?></span></td>
                                    <td class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($v['ville']) ?></td>
                                    <td>
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button onclick="openEditModal(<?= $v['id'] ?>, '<?= htmlspecialchars($v['ville'], ENT_QUOTES) ?>', <?= $v['region_id'] ?? 'null' ?>)"
                                                    class="act-btn act-btn-edit" data-tip="Modifier">
                                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                                            </button>
                                            <button onclick="confirmDelete(<?= $v['id'] ?>, '<?= htmlspecialchars($v['ville'], ENT_QUOTES) ?>')"
                                                    class="act-btn act-btn-delete" data-tip="Supprimer">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
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
<div id="editModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-md mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-brand-800 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm">
                <i class="fa-regular fa-pen-to-square mr-2"></i>Modifier la ville
            </h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="editForm" method="POST" action="/ville/update" class="p-6 space-y-5">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Région</label>
                <select name="region_id" id="edit_region_id" required class="w-full input">
                    <?php foreach ($regions as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Nom de la ville</label>
                <input type="text" name="nom" id="edit_nom" required class="w-full input">
            </div>
            <div class="flex space-x-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 btn btn-secondary py-3 text-sm">
                    Annuler
                </button>
                <button type="submit" class="flex-1 btn btn-primary py-3 text-sm">
                    <i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-sm mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-red-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm">
                <i class="fa-regular fa-circle-exclamation mr-2"></i>Confirmer la suppression
            </h3>
            <button onclick="closeDeleteModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-trash-can text-red-500 text-xl"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Êtes-vous sûr de vouloir supprimer</p>
            <p class="text-base font-bold text-gray-900 dark:text-white mt-1 mb-6" id="delete_name"></p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 btn btn-secondary py-3 text-sm">
                    Annuler
                </button>
                <a id="deleteLink" href="#" class="flex-1 btn btn-danger py-3 text-sm">
                    <i class="fa-regular fa-trash-can mr-2"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(id, nom, regionId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nom').value = nom;
    if (regionId) document.getElementById('edit_region_id').value = regionId;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); document.getElementById('editModal').classList.remove('flex'); }
function confirmDelete(id, nom) {
    document.getElementById('delete_name').textContent = nom + ' ?';
    document.getElementById('deleteLink').href = '/ville/delete/' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); document.getElementById('deleteModal').classList.remove('flex'); }
document.getElementById('editModal').addEventListener('click', function(e) { if (e.target === this) closeEditModal(); });
document.getElementById('deleteModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });
</script>
