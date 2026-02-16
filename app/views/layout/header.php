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
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'card': '0 0 0 1px rgba(0,0,0,0.02), 0 2px 4px rgba(0,0,0,0.05), 0 12px 24px rgba(0,0,0,0.05)',
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Sidebar */
        .sidebar-link { 
            position: relative;
            transition: all 0.2s ease;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: #fff;
            border-radius: 0 3px 3px 0;
            transition: height 0.2s ease;
        }
        .sidebar-link.active::before,
        .sidebar-link:hover::before {
            height: 60%;
        }
        .sidebar-link.active { 
            background: linear-gradient(90deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
        }
        
        /* Cards */
        .stat-card { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stat-card:hover::before { opacity: 1; }
        
        /* Tables */
        .table-pro thead th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .table-pro tbody tr {
            transition: all 0.15s ease;
        }
        .table-pro tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(37, 99, 235, 0.5);
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
            transition: all 0.2s ease;
        }
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.5);
        }
        
        /* Form inputs */
        .form-input {
            transition: all 0.2s ease;
            border: 1.5px solid #e2e8f0;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Progress bars */
        .progress-bar {
            background: linear-gradient(90deg, var(--bar-start) 0%, var(--bar-end) 100%);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.01em;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

    <!-- Sidebar + Main layout -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-72 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col shadow-2xl relative">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-0 w-24 h-24 bg-emerald-500 opacity-10 rounded-full blur-2xl"></div>
            
            <!-- Logo -->
            <div class="px-6 py-6 border-b border-slate-700/50 relative">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 ring-2 ring-white/20">
                        <i class="fa-solid fa-hands-holding-heart text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-wide bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">BNGRC</h1>
                        <p class="text-xs text-slate-400 font-medium">Suivi des dons</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold mb-3 px-3">Menu principal</p>
                
                <a href="/" class="sidebar-link group flex items-center px-4 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200 <?= $active === 'dashboard' ? 'active bg-white/10' : '' ?>">
                    <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 transition-all">
                        <i class="fa-solid fa-gauge-high text-white text-sm"></i>
                    </span>
                    <span class="text-slate-200 group-hover:text-white transition-colors">Tableau de bord</span>
                </a>
                
                <a href="/ville" class="sidebar-link group flex items-center px-4 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200 <?= $active === 'ville' ? 'active bg-white/10' : '' ?>">
                    <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mr-3 shadow-lg shadow-cyan-500/30 group-hover:shadow-cyan-500/50 transition-all">
                        <i class="fa-solid fa-city text-white text-sm"></i>
                    </span>
                    <span class="text-slate-200 group-hover:text-white transition-colors">Villes</span>
                </a>
                
                <a href="/besoin" class="sidebar-link group flex items-center px-4 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200 <?= $active === 'besoin' ? 'active bg-white/10' : '' ?>">
                    <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mr-3 shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/50 transition-all">
                        <i class="fa-solid fa-clipboard-list text-white text-sm"></i>
                    </span>
                    <span class="text-slate-200 group-hover:text-white transition-colors">Besoins</span>
                </a>
                
                <a href="/don" class="sidebar-link group flex items-center px-4 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200 <?= $active === 'don' ? 'active bg-white/10' : '' ?>">
                    <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center mr-3 shadow-lg shadow-emerald-500/30 group-hover:shadow-emerald-500/50 transition-all">
                        <i class="fa-solid fa-gift text-white text-sm"></i>
                    </span>
                    <span class="text-slate-200 group-hover:text-white transition-colors">Dons</span>
                </a>
                
                <a href="/dispatch" class="sidebar-link group flex items-center px-4 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-all duration-200 <?= $active === 'dispatch' ? 'active bg-white/10' : '' ?>">
                    <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-violet-600 flex items-center justify-center mr-3 shadow-lg shadow-purple-500/30 group-hover:shadow-purple-500/50 transition-all">
                        <i class="fa-solid fa-truck-fast text-white text-sm"></i>
                    </span>
                    <span class="text-slate-200 group-hover:text-white transition-colors">Dispatch</span>
                </a>
            </nav>

            <!-- Footer sidebar -->
            <div class="px-6 py-4 border-t border-slate-700/50">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        <i class="fa-regular fa-copyright mr-1"></i> 2026 BNGRC
                    </span>
                    <span class="text-[10px] text-slate-600 bg-slate-700/50 px-2 py-1 rounded-full">v1.0</span>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 flex flex-col bg-slate-50">
            <!-- Top bar -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/50 px-8 py-5 flex items-center justify-between sticky top-0 z-40">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        <?= htmlspecialchars($page_title) ?>
                    </h2>
                    <p class="text-sm text-slate-500 mt-0.5">Gestion des collectes et distributions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-sm text-slate-500 bg-slate-100 px-4 py-2 rounded-xl">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                        <span class="font-medium"><?= date('d M Y') ?></span>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-user text-white text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <div class="flex-1 p-8">
