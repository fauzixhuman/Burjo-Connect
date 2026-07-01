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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased pb-24">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-md mx-auto px-4 py-4 flex items-center justify-between">
            <a href="<?= base_url('/') ?>" class="flex-1">
                <img src="<?= base_url('images/logo.png') ?>" alt="Burjo Connect" class="w-48 sm:w-56 object-contain">
            </a>
            <a href="<?= base_url('cart') ?>" class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <?php
                    $cart = \Config\Services::cart();
                    $itemCount = $cart->totalItems();
                    if ($itemCount > 0):
                ?>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow"><?= $itemCount ?></span>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-md mx-auto w-full min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- FontAwesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
