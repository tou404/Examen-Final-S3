<?php
// ville.php - Gestion des villes
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BNGRC - Villes</title>
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
                <li class="nav-item"><a class="nav-link active" href="ville.php">Villes</a></li>
                <li class="nav-item"><a class="nav-link" href="besoin.php">Besoins</a></li>
                <li class="nav-item"><a class="nav-link" href="don.php">Dons</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Gestion des villes</h1>

    <div class="row">
        <!-- Formulaire ajout ville -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white"><strong>Ajouter une ville</strong></div>
                <div class="card-body">
                    <form method="post" action="ville.php">
                        <div class="mb-3">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select" id="region" name="region_id" required>
                                <option value="">-- Sélectionner --</option>
                                <?php // Options de régions à remplir depuis la base de données ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="nom" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des villes -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white"><strong>Liste des villes</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Région</th>
                                    <th>Ville</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // Boucle d'affichage des villes depuis la base de données ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucune ville pour le moment.</td>
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
