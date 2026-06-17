<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Warmindo Connect' ?></title>
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
            <div class="flex-1">
                <img src="<?= base_url('images/logo.png') ?>" alt="Warmindo Connect" class="w-48 sm:w-56 object-contain">
            </div>
            <?php if(isset($table)): ?>
            <div class="bg-orange-100 text-brand-primary px-3 py-1 rounded-full text-sm font-semibold">
                Meja <?= esc($table) ?>
            </div>
            <?php endif; ?>
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
