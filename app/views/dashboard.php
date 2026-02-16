<?php
// dashboard.php - Page d'accueil / Tableau de bord BNGRC
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BNGRC - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">BNGRC</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="ville.php">Villes</a></li>
                <li class="nav-item"><a class="nav-link" href="besoin.php">Besoins</a></li>
                <li class="nav-item"><a class="nav-link" href="don.php">Dons</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="mb-4">Tableau de bord</h1>

    <!-- Liste des villes avec synthèse -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <strong>Situation par ville</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Région</th>
                            <th>Ville</th>
                            <th>Besoins totaux (valeur)</th>
                            <th>Dons reçus (valeur)</th>
                            <th>Dons attribués (valeur)</th>
                            <th>Reste à couvrir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // Boucle d'affichage à remplir plus tard avec la base de données ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Données à venir (connexion BD non encore implémentée).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
