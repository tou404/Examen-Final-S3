<?php
// don.php - Gestion des dons
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BNGRC - Dons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">BNGRC</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="ville.php">Villes</a></li>
                <li class="nav-item"><a class="nav-link" href="besoin.php">Besoins</a></li>
                <li class="nav-item"><a class="nav-link active" href="don.php">Dons</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Gestion des dons</h1>

    <div class="row">
        <!-- Formulaire don -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-white"><strong>Ajouter un don</strong></div>
                <div class="card-body">
                    <form method="post" action="don.php">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type_besoin_id" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="1">Nature</option>
                                <option value="2">Matériaux</option>
                                <option value="3">Argent</option>
                                <?php // À terme, charger depuis la table type_de_besoin ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="designation" class="form-label">Désignation</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité ou montant</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="quantite" name="quantite_donnee" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date_saisie" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des dons -->
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-white"><strong>Liste des dons</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Désignation</th>
                                    <th>Quantité / Montant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // Boucle d'affichage des dons depuis la base de données ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun don pour le moment.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
