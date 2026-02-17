<?php
$page_title = 'Achats via dons en argent';
$active = 'achat';
include __DIR__ . '/layout/header.php';
?>

<!-- Messages -->
<?php if (!empty($message)): ?>
    <div class="mb-6 animate-fade-in">
        <div class="alert alert-success">
            <i class="fa-regular fa-circle-check mr-3"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="mb-6 animate-fade-in">
        <div class="alert alert-error">
            <i class="fa-regular fa-circle-xmark mr-3"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    </div>
<?php endif; ?>

<!-- Config Frais -->
<div class="mb-6 card bg-brand-800 dark:bg-brand-950 p-6 animate-fade-in">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-white">
            <h2 class="text-lg font-bold mb-1 flex items-center">
                <i class="fa-regular fa-circle-question mr-3"></i>Configuration des frais d'achat
            </h2>
            <p class="text-blue-200/70 text-sm">Frais actuel : <strong class="text-lg"><?= $fraisAchat ?>%</strong></p>
        </div>
        <form method="POST" action="/achat/frais" class="flex items-center gap-3">
            <input type="number" name="frais_achat" value="<?= $fraisAchat ?>" min="0" max="100" step="0.5"
                   class="w-20 px-3 py-2.5 rounded-lg border-0 bg-white/90 text-gray-800 font-bold text-center text-sm">
            <span class="text-white font-bold text-lg">%</span>
            <button type="submit" class="btn bg-white hover:bg-gray-50 text-brand-800 text-sm py-2.5 px-5">
                <i class="fa-regular fa-floppy-disk mr-1"></i>Modifier
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Formulaire d'achat -->
    <div class="card animate-fade-in" style="animation-delay: 0.1s">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center">
                    <i class="fa-regular fa-credit-card text-teal-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Effectuer un achat</h3>
                    <p class="text-xs text-gray-400">Utiliser un don en argent</p>
                </div>
            </div>
        </div>
        <form method="POST" action="/achat" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Filtrer par ville</label>
                <select id="filtre_ville" onchange="window.location.href='/achat?ville_id='+this.value" class="w-full input">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?= $v['id'] ?>" <?= $villeIdFiltre == $v['id'] ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Besoin à acheter <span class="text-red-400">*</span></label>
                <select name="besoin_id" required class="w-full input">
                    <option value="">Sélectionner...</option>
                    <?php foreach ($besoinsRestants as $b): ?>
                        <option value="<?= $b['id'] ?>" data-prix="<?= $b['prix_unitaire'] ?>" data-qte="<?= $b['quantite_restante'] ?>">
                            <?= htmlspecialchars($b['ville']) ?> - <?= htmlspecialchars($b['description']) ?> (<?= $b['quantite_restante'] ?> × <?= number_format($b['prix_unitaire'], 0, ',', ' ') ?> Ar)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Don en argent <span class="text-red-400">*</span></label>
                <select name="don_id" required class="w-full input">
                    <option value="">Sélectionner...</option>
                    <?php foreach ($donsArgent as $d): ?>
                        <option value="<?= $d['id'] ?>" data-montant="<?= $d['montant_restant'] ?>">
                            <?= htmlspecialchars($d['donateur']) ?> - <?= number_format($d['montant_restant'], 0, ',', ' ') ?> Ar
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Quantité <span class="text-red-400">*</span></label>
                <input type="number" name="quantite" id="quantite" min="1" required class="w-full input">
            </div>

            <!-- Aperçu -->
            <div id="apercu_cout" class="hidden rounded-lg bg-blue-50 dark:bg-blue-500/5 border border-blue-200 dark:border-blue-800 p-5">
                <p class="text-xs font-bold text-brand-600 dark:text-brand-400 mb-3 flex items-center uppercase tracking-wider">
                    <i class="fa-regular fa-calculator mr-2"></i>Aperçu du coût
                </p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Montant HT</span><span id="montant_ht" class="font-bold text-gray-900 dark:text-white">0</span></div>
                    <div class="flex justify-between"><span class="text-gray-500 dark:text-gray-400">Frais (<?= $fraisAchat ?>%)</span><span id="montant_frais" class="font-bold text-amber-600">0</span></div>
                    <div class="border-t border-blue-200 dark:border-blue-800 pt-2 flex justify-between"><span class="font-bold text-gray-700 dark:text-gray-300">Total TTC</span><span id="montant_total" class="font-bold text-brand-600 dark:text-brand-400 text-base">0</span></div>
                </div>
            </div>

            <button type="submit" class="w-full btn btn-teal py-3 text-sm">
                <i class="fa-regular fa-credit-card mr-2"></i>Effectuer l'achat
            </button>
        </form>
    </div>

    <!-- Dons disponibles -->
    <div class="card animate-fade-in" style="animation-delay: 0.15s">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                    <i class="fa-regular fa-money-bill-1 text-emerald-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Dons en argent disponibles</h3>
                    <p class="text-xs text-gray-400"><?= count($donsArgent) ?> don(s)</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <?php if (empty($donsArgent)): ?>
                <div class="empty-state text-center">
                    <div class="empty-icon"><i class="fa-regular fa-money-bill-1 text-gray-400 text-xl"></i></div>
                    <p class="text-sm font-semibold text-gray-400">Aucun don disponible</p>
                </div>
            <?php else: ?>
                <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    <?php foreach ($donsArgent as $d): ?>
                        <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-800 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($d['donateur']) ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($d['designation']) ?></p>
                                </div>
                                <p class="text-sm font-bold text-emerald-600"><?= number_format($d['montant_restant'], 0, ',', ' ') ?> Ar</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Historique achats -->
<div class="mt-6 card overflow-hidden animate-fade-in" style="animation-delay: 0.2s">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center">
                <i class="fa-regular fa-file-lines text-purple-600 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Historique des achats</h3>
                <p class="text-xs text-gray-400">Achats effectués</p>
            </div>
        </div>
        <span class="badge bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400"><?= count($achats) ?> achat(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Besoin</th>
                    <th class="text-left">Ville</th>
                    <th class="text-left">Donateur</th>
                    <th class="text-center">Qté</th>
                    <th class="text-right">HT</th>
                    <th class="text-right">Frais</th>
                    <th class="text-right">Total</th>
                    <th class="text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($achats)): ?>
                    <tr><td colspan="9" class="empty-state text-center">
                        <div class="empty-icon"><i class="fa-regular fa-credit-card text-gray-400 text-xl"></i></div>
                        <p class="text-sm font-semibold text-gray-400">Aucun achat effectué</p>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($achats as $i => $a): ?>
                        <tr>
                            <td class="text-gray-400 font-medium text-xs"><?= $i + 1 ?></td>
                            <td class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($a['besoin_description']) ?></td>
                            <td><span class="badge bg-blue-50 dark:bg-blue-500/10 text-brand-600 dark:text-brand-400"><?= htmlspecialchars($a['ville']) ?></span></td>
                            <td class="text-gray-600 dark:text-gray-300"><?= htmlspecialchars($a['donateur']) ?></td>
                            <td class="text-center"><span class="badge bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold"><?= $a['quantite_achetee'] ?></span></td>
                            <td class="text-right text-gray-600 dark:text-gray-300 font-medium"><?= number_format($a['montant_ht'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-right font-bold text-amber-600"><?= number_format($a['montant_frais'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-right font-bold text-gray-900 dark:text-white"><?= number_format($a['montant_total'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-gray-400 text-sm"><?= date('d/m/Y H:i', strtotime($a['date_achat'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const besoinSelect = document.querySelector('select[name="besoin_id"]');
const quantiteInput = document.getElementById('quantite');
const apercuDiv = document.getElementById('apercu_cout');
const fraisPct = <?= $fraisAchat ?>;

function calculerCout() {
    const besoinOption = besoinSelect.options[besoinSelect.selectedIndex];
    const prix = parseFloat(besoinOption.dataset.prix) || 0;
    const qte = parseInt(quantiteInput.value) || 0;
    if (prix > 0 && qte > 0) {
        const ht = prix * qte;
        const frais = ht * (fraisPct / 100);
        const total = ht + frais;
        document.getElementById('montant_ht').textContent = ht.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('montant_frais').textContent = frais.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('montant_total').textContent = total.toLocaleString('fr-FR') + ' Ar';
        apercuDiv.classList.remove('hidden');
    } else {
        apercuDiv.classList.add('hidden');
    }
}
besoinSelect.addEventListener('change', calculerCout);
quantiteInput.addEventListener('input', calculerCout);
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
