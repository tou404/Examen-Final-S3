<?php
$page_title = 'Gestion des dons';
$active = 'don';
include __DIR__ . '/layout/header.php';
?>

<!-- Header + Toggle Button -->
<div class="flex items-center justify-between mb-6 animate-fade-in">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
            <i class="fa-regular fa-heart text-emerald-600 text-lg"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Dons reçus</h3>
            <p class="text-xs text-gray-400"><?= count($dons) ?> don(s) enregistré(s)</p>
        </div>
    </div>
    <button onclick="toggleForm()" id="toggleFormBtn" class="btn btn-primary py-2.5 px-5 text-sm">
        <i class="fa-regular fa-square-plus mr-2" id="toggleFormIcon"></i>
        <span id="toggleFormText">Nouveau don</span>
    </button>
</div>

<!-- Formulaire (caché par défaut) -->
<div id="formSection" style="display:none;" class="mb-6 animate-fade-in">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                    <i class="fa-regular fa-pen-to-square text-emerald-600 text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Enregistrer un don</h3>
            </div>
            <button onclick="toggleForm()" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center text-gray-400 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <div class="p-6">
            <form method="post" action="/don" class="space-y-5">
                <!-- Ligne 1 : Donateur -->
                <div>
                    <p class="text-[10px] font-bold text-brand-600 uppercase tracking-widest flex items-center pb-2 mb-3 border-b border-gray-100 dark:border-gray-700">
                        <i class="fa-regular fa-user mr-2"></i>Informations du donateur
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Prénom <span class="text-red-400">*</span></label>
                            <input type="text" name="prenom" required class="w-full input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nom <span class="text-red-400">*</span></label>
                            <input type="text" name="nom" required class="w-full input">
                        </div>
                    </div>
                </div>
                <!-- Ligne 2 : Don -->
                <div>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest flex items-center pb-2 mb-3 border-b border-gray-100 dark:border-gray-700">
                        <i class="fa-regular fa-gem mr-2"></i>Détails du don
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Type <span class="text-red-400">*</span></label>
                            <select name="type_besoin_id" required class="w-full input">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($types as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Désignation</label>
                            <input type="text" name="designation" placeholder="Ex: Riz, Tentes..." class="w-full input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Quantité</label>
                            <input type="number" min="0" name="quantite" value="0" class="w-full input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Montant (Ar)</label>
                            <input type="number" min="0" step="0.01" name="montant" value="0" class="w-full input">
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="button" onclick="toggleForm()" class="btn btn-secondary py-2.5 px-5 text-sm mr-3">Annuler</button>
                    <button type="submit" class="btn btn-success py-2.5 px-6 text-sm">
                        <i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer le don
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Liste des dons -->
<div class="animate-fade-in-delayed">
    <?php if (empty($dons)): ?>
        <div class="card p-12 text-center">
            <div class="empty-icon mx-auto"><i class="fa-regular fa-heart text-gray-400 text-xl"></i></div>
            <p class="text-sm font-semibold text-gray-400 mt-3">Aucun don enregistré</p>
            <p class="text-xs text-gray-300 dark:text-gray-600 mt-1">Cliquez sur "Nouveau don" pour commencer</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($dons as $i => $d): ?>
                <?php
                    $hasMontant = !empty($d['montant']) && $d['montant'] > 0;
                    $hasQte = !empty($d['quantite']) && $d['quantite'] > 0;
                    $nom = htmlspecialchars($d['donateur'] ?? 'Anonyme');
                    $initiale = strtoupper(substr($nom, 0, 1));
                ?>
                <div class="card hover:shadow-md transition-all duration-200 group">
                    <div class="p-4 flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-11 h-11 rounded-full bg-brand-800 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-sm font-bold"><?= $initiale ?></span>
                        </div>

                        <!-- Infos -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate"><?= $nom ?></p>
                                <span class="badge bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px]">
                                    <?= htmlspecialchars($d['type']) ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                <?php if (!empty($d['designation'])): ?>
                                    <span class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($d['designation']) ?></span>
                                    <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                                <?php endif; ?>
                                <span class="text-[11px] text-gray-400 flex items-center">
                                    <i class="fa-regular fa-calendar mr-1"></i><?= date('d/m/Y', strtotime($d['date_don'])) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Valeurs -->
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <?php if ($hasQte): ?>
                                <div class="text-center px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-500/5">
                                    <p class="text-sm font-bold text-brand-600 dark:text-brand-400"><?= $d['quantite'] ?></p>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-wider font-semibold">Qté</p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hasMontant): ?>
                                <div class="text-center px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/5">
                                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400"><?= number_format($d['montant'], 0, ',', ' ') ?></p>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-wider font-semibold">Ariary</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex-shrink-0">
                            <button onclick="openEditDonModal(<?= $d['id'] ?>, '<?= htmlspecialchars($d['designation'] ?? '', ENT_QUOTES) ?>', <?= intval($d['quantite']) ?>, <?= floatval($d['montant']) ?>)"
                                    class="act-btn act-btn-edit" title="Modifier"><i class="fa-regular fa-pen-to-square text-xs"></i></button>
                            <button onclick="confirmDeleteDon(<?= $d['id'] ?>, '<?= htmlspecialchars($d['designation'] ?? 'ce don', ENT_QUOTES) ?>')"
                                    class="act-btn act-btn-delete" title="Supprimer"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Edit -->
<div id="editDonModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-md mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-emerald-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm"><i class="fa-regular fa-pen-to-square mr-2"></i>Modifier le don</h3>
            <button onclick="closeEditDonModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition"><i class="fa-solid fa-xmark text-sm"></i></button>
        </div>
        <form method="POST" action="/don/update" class="p-6 space-y-4">
            <input type="hidden" name="id" id="edit_don_id">
            <input type="hidden" name="type_besoin_id" id="edit_don_type_id" value="3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Désignation</label>
                <input type="text" name="designation" id="edit_don_designation" class="w-full input">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité</label>
                    <input type="number" min="0" name="quantite" id="edit_don_quantite" class="w-full input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Montant (Ar)</label>
                    <input type="number" min="0" step="0.01" name="montant" id="edit_don_montant" class="w-full input">
                </div>
            </div>
            <div class="flex space-x-3 pt-2">
                <button type="button" onclick="closeEditDonModal()" class="flex-1 btn btn-secondary py-3 text-sm">Annuler</button>
                <button type="submit" class="flex-1 btn btn-success py-3 text-sm"><i class="fa-regular fa-floppy-disk mr-2"></i>Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteDonModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-box bg-white dark:bg-[#1e293b] w-full max-w-sm mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 bg-red-600 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center text-sm"><i class="fa-regular fa-circle-exclamation mr-2"></i>Confirmer la suppression</h3>
            <button onclick="closeDeleteDonModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition"><i class="fa-solid fa-xmark text-sm"></i></button>
        </div>
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center mx-auto mb-4"><i class="fa-regular fa-trash-can text-red-500 text-xl"></i></div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Êtes-vous sûr de vouloir supprimer</p>
            <p class="text-base font-bold text-gray-900 dark:text-white mt-1 mb-6" id="delete_don_name"></p>
            <div class="flex space-x-3">
                <button type="button" onclick="closeDeleteDonModal()" class="flex-1 btn btn-secondary py-3 text-sm">Annuler</button>
                <a id="deleteDonLink" href="#" class="flex-1 btn btn-danger py-3 text-sm"><i class="fa-regular fa-trash-can mr-2"></i>Supprimer</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForm() {
    var section = document.getElementById('formSection');
    var btnText = document.getElementById('toggleFormText');
    var btnIcon = document.getElementById('toggleFormIcon');
    var btn = document.getElementById('toggleFormBtn');
    if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block';
        btnText.textContent = 'Fermer';
        btnIcon.className = 'fa-solid fa-xmark mr-2';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    } else {
        section.style.display = 'none';
        btnText.textContent = 'Nouveau don';
        btnIcon.className = 'fa-regular fa-square-plus mr-2';
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
    }
}
function openEditDonModal(id, designation, quantite, montant) {
    document.getElementById('edit_don_id').value = id;
    document.getElementById('edit_don_designation').value = designation;
    document.getElementById('edit_don_quantite').value = quantite;
    document.getElementById('edit_don_montant').value = montant;
    document.getElementById('editDonModal').classList.remove('hidden');
    document.getElementById('editDonModal').classList.add('flex');
}
function closeEditDonModal() {
    document.getElementById('editDonModal').classList.add('hidden');
    document.getElementById('editDonModal').classList.remove('flex');
}
function confirmDeleteDon(id, nom) {
    document.getElementById('delete_don_name').textContent = nom + ' ?';
    document.getElementById('deleteDonLink').href = '/don/delete/' + id;
    document.getElementById('deleteDonModal').classList.remove('hidden');
    document.getElementById('deleteDonModal').classList.add('flex');
}
function closeDeleteDonModal() {
    document.getElementById('deleteDonModal').classList.add('hidden');
    document.getElementById('deleteDonModal').classList.remove('flex');
}
document.getElementById('editDonModal').addEventListener('click', function(e) { if (e.target === this) closeEditDonModal(); });
document.getElementById('deleteDonModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteDonModal(); });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
