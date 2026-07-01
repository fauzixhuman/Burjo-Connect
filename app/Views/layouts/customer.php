<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Burjo Connect' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('images/favicon.png') ?>">
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #FAFAFA; }
        .sidebar-link { transition: all 0.2s ease; border-radius: 10px; }
        .sidebar-link:hover { background-color: #F3F4F6; color: #111827; }
        .sidebar-link.active { background-color: #FFFBEB; color: #D97706; font-weight: 600; }
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
                    <h1 class="font-bold text-gray-900 tracking-tight leading-tight">Burjo Connect</h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1.5">
                <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 mt-4">Menu Utama</p>
                
                <a href="<?= base_url('/') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium <?= ($activePage ?? '') === 'menu' ? 'active' : 'text-gray-500' ?>">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Katalog Menu
                </a>
                <a href="<?= base_url('/cart') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium <?= ($activePage ?? '') === 'cart' ? 'active' : 'text-gray-500' ?>">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Keranjang
                </a>
            </nav>

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
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight"><?= $pageTitle ?? 'Pesan Menu' ?></h2>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Cart Button with Badge -->
                    <a href="<?= base_url('/cart') ?>" class="relative text-gray-500 hover:text-amber-500 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <?php 
                            $cart = \Config\Services::cart(); 
                            $itemCount = $cart->totalItems();
                            if($itemCount > 0): 
                        ?>
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white"><?= $itemCount ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- User Avatar -->
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-900">Pelanggan</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden text-white font-bold">
                            P
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 lg:hidden">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"></div>
                <div class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl z-50 flex flex-col transition-transform transform">
                    <div class="py-6 flex items-center justify-between px-6 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <img src="<?= base_url('images/favicon.png') ?>" alt="Logo" class="w-16 h-16 object-contain">
                            <h1 class="font-bold text-gray-900 tracking-tight">Burjo Connect</h1>
                        </div>
                        <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="<?= base_url('/') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?= ($activePage ?? '') === 'menu' ? 'active' : 'text-gray-500' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Katalog Menu
                        </a>
                        <a href="<?= base_url('/cart') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?= ($activePage ?? '') === 'cart' ? 'active' : 'text-gray-500' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Keranjang
                        </a>
                    </nav>
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
