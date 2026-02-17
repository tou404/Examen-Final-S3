<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'BNGRC') ?> — BNGRC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e3a8a',
                            900: '#1e3050',
                            950: '#0f1729'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* ==================== INSTITUTIONAL DESIGN SYSTEM ==================== */

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.4s ease-out both; }
        .animate-fade-in-delayed { animation: fadeInUp 0.4s ease-out 0.1s both; }

        /* Sidebar */
        .sidebar {
            background: #1e3a8a;
            border-right: 1px solid rgba(255,255,255,0.08);
        }
        .dark .sidebar {
            background: #0f172a;
            border-right-color: #1e293b;
        }

        /* Nav items */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-link:hover {
            color: rgba(255,255,255,0.95);
            background: rgba(255,255,255,0.08);
        }
        .nav-link.active {
            color: #ffffff;
            background: rgba(255,255,255,0.12);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: #60a5fa;
            border-radius: 0 4px 4px 0;
        }
        .nav-link i { width: 20px; text-align: center; margin-right: 12px; font-size: 14px; opacity: 0.7; }
        .nav-link.active i { opacity: 1; }

        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .dark .card {
            background: #1e293b;
            border-color: #334155;
        }
        .card:hover {
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
        }
        .dark .card:hover {
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.3);
        }

        /* Stat cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px 24px;
            transition: all 0.3s ease;
        }
        .dark .stat-card {
            background: #1e293b;
            border-color: #334155;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -8px rgba(0, 0, 0, 0.1);
        }
        .dark .stat-card:hover {
            box-shadow: 0 8px 24px -8px rgba(0, 0, 0, 0.4);
        }

        /* Tables */
        .tbl th {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.06em;
            padding: 12px 16px;
        }
        .dark .tbl th {
            background: #0f172a;
            color: #94a3b8;
        }
        .tbl td {
            font-size: 0.8125rem;
            padding: 12px 16px;
            vertical-align: middle;
        }
        .tbl tbody tr {
            transition: background 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        .dark .tbl tbody tr {
            border-bottom-color: #1e293b;
        }
        .tbl tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .dark .tbl tbody tr:nth-child(even) {
            background: rgba(30, 41, 59, 0.5);
        }
        .tbl tbody tr:hover {
            background: #eff6ff;
        }
        .dark .tbl tbody tr:hover {
            background: rgba(59, 130, 246, 0.06);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8125rem;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .btn-primary {
            background: #1e3a8a;
            color: #ffffff;
            box-shadow: 0 2px 8px -2px rgba(30, 58, 138, 0.4);
        }
        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px -2px rgba(30, 58, 138, 0.5);
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover { background: #e5e7eb; }
        .dark .btn-secondary {
            background: #334155;
            color: #e2e8f0;
            border-color: #475569;
        }
        .dark .btn-secondary:hover { background: #475569; }

        .btn-success {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 2px 8px -2px rgba(5, 150, 105, 0.4);
        }
        .btn-success:hover { background: #047857; }
        .btn-danger {
            background: #dc2626;
            color: #ffffff;
            box-shadow: 0 2px 8px -2px rgba(220, 38, 38, 0.4);
        }
        .btn-danger:hover { background: #b91c1c; }
        .btn-warning {
            background: #d97706;
            color: #ffffff;
            box-shadow: 0 2px 8px -2px rgba(217, 119, 6, 0.4);
        }
        .btn-warning:hover { background: #b45309; }
        .btn-teal {
            background: #0d9488;
            color: #ffffff;
            box-shadow: 0 2px 8px -2px rgba(13, 148, 136, 0.4);
        }
        .btn-teal:hover { background: #0f766e; }

        /* Inputs */
        .input {
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.8125rem;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #111827;
        }
        .dark .input {
            border-color: #475569;
            background: #0f172a;
            color: #e2e8f0;
        }
        .input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        .dark .input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Progress bar */
        .progress-bar {
            height: 6px;
            border-radius: 6px;
            background: #e5e7eb;
            overflow: hidden;
        }
        .dark .progress-bar { background: #334155; }
        .progress-bar .fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.8s ease;
        }
        .fill-green { background: #059669; }
        .fill-amber { background: #d97706; }
        .fill-red { background: #dc2626; }
        .fill-blue { background: #3b82f6; }

        /* Action buttons */
        .act-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .act-btn:hover { transform: scale(1.08); }
        .act-btn-edit {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        .act-btn-edit:hover { background: rgba(59, 130, 246, 0.2); }
        .dark .act-btn-edit { background: rgba(96, 165, 250, 0.12); color: #60a5fa; }
        .act-btn-delete {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }
        .act-btn-delete:hover { background: rgba(220, 38, 38, 0.2); }
        .dark .act-btn-delete { background: rgba(248, 113, 113, 0.12); color: #f87171; }

        /* Modal */
        .modal-bg {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal-box {
            border-radius: 12px;
            box-shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.25);
            animation: modalIn 0.25s ease-out;
        }

        /* Empty state */
        .empty-state { padding: 40px 20px; }
        .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }
        .dark .empty-icon { background: #1e293b; }

        /* Theme toggle */
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            border-radius: 12px;
            background: #d1d5db;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .dark .toggle-switch { background: #3b82f6; }
        .toggle-switch .knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .dark .toggle-switch .knob { transform: translateX(20px); }
        .toggle-switch .knob i.fa-sun { color: #f59e0b; }
        .dark .toggle-switch .knob i.fa-sun { display: none; }
        .toggle-switch .knob i.fa-moon { display: none; color: #3b82f6; }
        .dark .toggle-switch .knob i.fa-moon { display: inline; }

        /* Alert messages */
        .alert {
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            font-size: 0.8125rem;
            font-weight: 500;
            border-left: 4px solid;
        }
        .alert-success { background: #f0fdf4; border-color: #059669; color: #166534; }
        .dark .alert-success { background: rgba(5,150,105,0.08); color: #6ee7b7; }
        .alert-error { background: #fef2f2; border-color: #dc2626; color: #991b1b; }
        .dark .alert-error { background: rgba(220,38,38,0.08); color: #fca5a5; }
        .alert-info { background: #eff6ff; border-color: #3b82f6; color: #1e3a8a; }
        .dark .alert-info { background: rgba(59,130,246,0.08); color: #93c5fd; }

        /* Tooltip */
        [data-tip] { position: relative; }
        [data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 10px;
            background: #1f2937;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 500;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 50;
        }
        .dark [data-tip]:hover::after { background: #475569; }

        /* Print */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
        }
    </style>
    <script>
        function initTheme() {
            const theme = localStorage.getItem('bngrc-theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            }
        }
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            document.documentElement.classList.toggle('light', !isDark);
            localStorage.setItem('bngrc-theme', isDark ? 'dark' : 'light');
        }
        initTheme();
    </script>
</head>
<body class="bg-[#f3f4f6] dark:bg-[#0f172a] min-h-screen antialiased text-gray-700 dark:text-gray-300 transition-colors duration-200">

    <div class="flex min-h-screen">

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar w-60 flex flex-col no-print fixed h-full z-50">

            <!-- Logo -->
            <div class="h-16 flex items-center px-5 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center">
                        <i class="fa-regular fa-heart text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-white tracking-wide">BNGRC</h1>
                        <p class="text-[10px] text-blue-300/60 font-medium">Suivi des dons</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-4 text-[9px] font-semibold text-blue-300/40 uppercase tracking-[0.12em] mb-2">Menu principal</p>

                <a href="/" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-regular fa-chart-bar"></i>Dashboard
                </a>
                <a href="/ville" class="nav-link <?= $active === 'ville' ? 'active' : '' ?>">
                    <i class="fa-regular fa-building"></i>Villes
                </a>
                <a href="/besoin" class="nav-link <?= $active === 'besoin' ? 'active' : '' ?>">
                    <i class="fa-regular fa-clipboard"></i>Besoins
                </a>
                <a href="/don" class="nav-link <?= $active === 'don' ? 'active' : '' ?>">
                    <i class="fa-regular fa-heart"></i>Dons
                </a>
                <a href="/dispatch" class="nav-link <?= $active === 'dispatch' ? 'active' : '' ?>">
                    <i class="fa-regular fa-paper-plane"></i>Dispatch
                </a>
                <a href="/achat" class="nav-link <?= $active === 'achat' ? 'active' : '' ?>">
                    <i class="fa-regular fa-credit-card"></i>Achats
                </a>
                <a href="/distribution" class="nav-link <?= $active === 'distribution' ? 'active' : '' ?>">
                    <i class="fa-regular fa-hand-holding-heart"></i>Distribution
                </a>

                <p class="px-4 text-[9px] font-semibold text-blue-300/40 uppercase tracking-[0.12em] mt-5 mb-2">Rapports</p>

                <a href="/recap" class="nav-link <?= $active === 'recap' ? 'active' : '' ?>">
                    <i class="fa-regular fa-file-lines"></i>Récapitulation
                </a>
            </nav>

            <!-- Footer -->
            <div class="px-4 py-3 border-t border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-blue-300/40 font-medium">© 2025 BNGRC</span>
                    <span class="text-[9px] px-2 py-0.5 rounded bg-white/10 text-blue-200/60 font-semibold">v2.0</span>
                </div>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="flex-1 flex flex-col min-w-0 ml-60">

            <!-- Top bar -->
            <header class="h-16 bg-white dark:bg-[#1e293b] border-b border-gray-200 dark:border-gray-700/50 px-6 flex items-center justify-between sticky top-0 z-40 no-print">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($page_title) ?></h2>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">Bureau National de Gestion des Risques et Catastrophes</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Date -->
                    <div class="hidden sm:flex items-center text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg font-medium">
                        <i class="fa-regular fa-calendar mr-2 text-brand-500"></i>
                        <?= date('d M Y') ?>
                    </div>
                    <!-- Theme toggle -->
                    <div class="toggle-switch" onclick="toggleTheme()" title="Changer de thème">
                        <div class="knob">
                            <i class="fa-solid fa-sun"></i>
                            <i class="fa-solid fa-moon"></i>
                        </div>
                    </div>
                    <!-- User -->
                    <div class="w-9 h-9 rounded-lg bg-brand-800 flex items-center justify-center">
                        <i class="fa-regular fa-user text-white text-xs"></i>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <div class="flex-1 p-6 overflow-auto">
