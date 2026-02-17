<?php
$page_title = 'Gestion des besoins';
$active = 'besoin';
include __DIR__ . '/layout/header.php';

$totalB = count($besoins);
$totalQte = 0; $totalVal = 0; $totalRest = 0;
foreach ($besoins as $b) {
    $totalQte += intval($b['quantite']);
    $totalVal += floatval($b['prix_unitaire']) * intval($b['quantite']);
    $totalRest += intval($b['quantite_restante'] ?? 0);
}
$pct = $totalQte > 0 ? round((($totalQte - $totalRest) / $totalQte) * 100) : 0;
?>

<!-- Mini stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Besoins</p>
                <p class="text-xl font-extrabold text-gray-900 dark:text-white mt-0.5"><?= $totalB ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fa-regular fa-clipboard text-white text-sm"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay:.05s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Quantité</p>
                <p class="text-xl font-extrabold text-gray-900 dark:text-white mt-0.5"><?= number_format($totalQte, 0, ',', ' ') ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fa-regular fa-layer-group text-white text-sm"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay:.1s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Valeur</p>
                <p class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5"><?= number_format($totalVal, 0, ',', ' ') ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fa-regular fa-money-bill-1 text-white text-sm"></i>
            </div>
        </div>
    </div>
    <div class="stat-card animate-fade-in" style="animation-delay:.15s">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Couvert</p>
                <p class="text-xl font-extrabold <?= $pct >= 75 ? 'text-emerald-600' : ($pct >= 40 ? 'text-amber-600' : 'text-red-500') ?> mt-0.5"><?= $pct ?>%</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?= $pct >= 75 ? 'from-emerald-400 to-green-500 shadow-emerald-500/20' : ($pct >= 40 ? 'from-amber-400 to-yellow-500 shadow-amber-500/20' : 'from-red-400 to-rose-500 shadow-red-500/20') ?> flex items-center justify-center shadow-lg">
                <i class="fa-regular fa-chart-pie text-white text-sm"></i>
            </div>
        </div>
        <div class="mt-2 progress-bar !h-1.5">
            <div class="fill <?= $pct >= 75 ? 'fill-green' : ($pct >= 40 ? 'fill-amber' : 'fill-red') ?>" style="width:<?= min($pct, 100) ?>%"></div>
        </div>
    </div>
</div>

<!-- Bouton toggle formulaire -->
<div class="mb-5 animate-fade-in" style="animation-delay:.2s">
    <button onclick="document.getElementById('formBesoin').classList.toggle('hidden')" class="btn bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white py-2.5 px-5 text-sm shadow-lg shadow-amber-500/25">
        <i class="fa-regular fa-square-plus mr-2"></i>Nouveau besoin
    </button>
</div>

<!-- Formulaire (masqué par défaut) -->
<div id="formBesoin" class="hidden mb-6 animate-fade-in">
    <div class="card border-t-4 !border-t-amber-500">
        <div class="p-6">
            <form method="post" action="/besoin" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
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
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Prix unit. (Ar) <span class="text-red-400">*</span></label>
                    <input type="number" step="0.01" min="0" name="prix_unitaire" required class="w-full input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité <span class="text-red-400">*</span></label>
                    <input type="number" min="1" name="quantite" required class="w-full input">
                </div>
                <div>
                    <button type="submit" class="w-full btn bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white py-2.5 text-sm shadow-lg shadow-amber-500/25">
                        <i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Liste des besoins -->
<div class="card overflow-hidden animate-fade-in" style="animation-delay:.25s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fa-regular fa-clipboard text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Liste des besoins</h3>
                <p class="text-xs text-gray-400">Tous les besoins enregistrés</p>
            </div>
        </div>
        <span class="px-3 py-1.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold shadow-sm"><?= count($besoins) ?> besoin(s)</span>
    </div>
            <div class="overflow-x-auto">
                <table class="w-full tbl">
                    <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-center">Ordre</th>
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
                            <tr><td colspan="9" class="empty-state text-center">
                                <div class="empty-icon"><i class="fa-regular fa-clipboard text-gray-400 text-xl"></i></div>
                                <p class="text-sm font-semibold text-gray-400">Aucun besoin enregistré</p>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($besoins as $i => $b): ?>
                                <tr>
                                    <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                                    <td class="text-center font-bold text-brand-600"><?= $b['ordre'] ?? '-' ?></td>
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
