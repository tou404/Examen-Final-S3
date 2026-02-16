<?php
/**
 * Layout principal - Tailwind CSS + FontAwesome 7.1.0 local
 * Variables attendues : $page_title, $active (nom de la page active)
 */
$active = $active ?? '';
$page_title = $page_title ?? 'BNGRC';
$base = Flight::get('flight.base_url') ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - BNGRC</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 7.1.0 local -->
    <link rel="stylesheet" href="/fontawesome-free-7.1.0-web/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar-link.active { background-color: rgba(255,255,255,0.15); border-left: 3px solid #fff; }
        .stat-card { transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Sidebar + Main layout -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex flex-col shadow-xl">
            <!-- Logo -->
            <div class="px-6 py-5 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-hands-holding-heart text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-wide">BNGRC</h1>
                        <p class="text-xs text-blue-200">Suivi des dons</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="/" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-10 transition <?= $active === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-gauge-high w-5 mr-3 text-center"></i>
                    Tableau de bord
                </a>
                <a href="/ville" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-10 transition <?= $active === 'ville' ? 'active' : '' ?>">
                    <i class="fa-solid fa-city w-5 mr-3 text-center"></i>
                    Villes
                </a>
                <a href="/besoin" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-10 transition <?= $active === 'besoin' ? 'active' : '' ?>">
                    <i class="fa-solid fa-clipboard-list w-5 mr-3 text-center"></i>
                    Besoins
                </a>
                <a href="/don" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-10 transition <?= $active === 'don' ? 'active' : '' ?>">
                    <i class="fa-solid fa-gift w-5 mr-3 text-center"></i>
                    Dons
                </a>
                <a href="/dispatch" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:bg-opacity-10 transition <?= $active === 'dispatch' ? 'active' : '' ?>">
                    <i class="fa-solid fa-truck-fast w-5 mr-3 text-center"></i>
                    Dispatch
                </a>
            </nav>

            <!-- Footer sidebar -->
            <div class="px-4 py-3 border-t border-blue-700 text-xs text-blue-300">
                <i class="fa-regular fa-copyright mr-1"></i> 2026 BNGRC
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 flex flex-col">
            <!-- Top bar -->
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">
                    <?= htmlspecialchars($page_title) ?>
                </h2>
                <div class="flex items-center space-x-3 text-sm text-gray-500">
                    <i class="fa-regular fa-calendar"></i>
                    <span><?= date('d/m/Y') ?></span>
                </div>
            </header>

            <!-- Page content -->
            <div class="flex-1 p-6">
