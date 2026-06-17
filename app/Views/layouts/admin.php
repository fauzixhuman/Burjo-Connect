<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Warmindo Connect' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('images/favicon.png') ?>">
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #FAFAFA; }
        .sidebar-link { transition: all 0.2s ease; border-radius: 10px; }
        .sidebar-link:hover { background-color: #F3F4F6; color: #111827; }
        .sidebar-link.active { background-color: #FFFBEB; color: #D97706; font-weight: 600; }
        .stat-card { transition: all 0.3s ease; border: 1px solid #F3F4F6; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: #E5E7EB; }
        .table-row { transition: background-color 0.2s ease; }
        .table-row:hover { background-color: #F9FAFB; }
        .status-badge { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 10px; border-radius: 20px; }
        .status-pending { background: #FEF3C7; color: #B45309; }
        .status-cooking { background: #DBEAFE; color: #1D4ED8; }
        .status-ready { background: #D1FAE5; color: #047857; }
        .status-completed { background: #F3F4F6; color: #4B5563; }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <div class="flex min-h-screen">

        <!-- Sidebar (Desktop) -->
        <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-100 fixed inset-y-0 left-0 z-50">
            <!-- Logo -->
            <div class="py-6 flex items-center gap-4 px-8">
                <img src="<?= base_url('images/favicon.png') ?>" alt="Logo" class="w-20 h-20 object-contain">
                <div>
                    <h1 class="font-bold text-gray-900 tracking-tight leading-tight">Warmindo</h1>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Workspace</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1.5">
                <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 mt-4">Menu Utama</p>
                
                <a href="<?= base_url('admin') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium <?= ($activePage ?? '') === 'dashboard' ? 'active' : 'text-gray-500' ?>">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="<?= base_url('admin/orders') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium <?= ($activePage ?? '') === 'orders' ? 'active' : 'text-gray-500' ?>">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Pesanan Masuk
                </a>
                <a href="<?= base_url('admin/menus') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium <?= ($activePage ?? '') === 'menus' ? 'active' : 'text-gray-500' ?>">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Katalog Menu
                </a>
            </nav>

            <!-- Footer -->
            <div class="px-6 py-6 mt-auto space-y-2">
                <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl text-xs font-semibold transition-colors border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Tampilan Pelanggan
                </a>
                <a href="<?= base_url('admin/logout') ?>" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-xl text-xs font-semibold transition-colors border border-red-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 h-20 flex items-center justify-between px-8 sticky top-0 z-30">
                <!-- Mobile Menu Button -->
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-100 text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight"><?= $pageTitle ?? 'Dashboard' ?></h2>
                </div>

                <div class="flex items-center gap-4">
                    <button class="w-10 h-10 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-gray-400 hover:text-amber-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=111827&color=fff" alt="Admin" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 lg:hidden">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"></div>
                <div class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl z-50 flex flex-col transition-transform transform">
                    <div class="py-6 flex items-center justify-between px-6 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <img src="<?= base_url('images/favicon.png') ?>" alt="Logo" class="w-20 h-20 object-contain">
                            <h1 class="font-bold text-gray-900 tracking-tight">Warmindo</h1>
                        </div>
                        <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="<?= base_url('admin') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?= ($activePage ?? '') === 'dashboard' ? 'active' : 'text-gray-500' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                        <a href="<?= base_url('admin/orders') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?= ($activePage ?? '') === 'orders' ? 'active' : 'text-gray-500' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Pesanan Masuk
                        </a>
                        <a href="<?= base_url('admin/menus') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?= ($activePage ?? '') === 'menus' ? 'active' : 'text-gray-500' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Katalog Menu
                        </a>
                    </nav>

                    <div class="p-4 border-t border-gray-100">
                        <a href="<?= base_url('admin/logout') ?>" class="flex items-center justify-center gap-2 w-full py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-sm font-semibold transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-8 max-w-7xl mx-auto">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
