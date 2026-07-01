<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<?php
// Ekstrak kategori unik dari data menu
$categories = [];
if (!empty($menus)) {
    foreach ($menus as $menu) {
        if (!empty($menu['category_name']) && !in_array($menu['category_name'], $categories)) {
            $categories[] = $menu['category_name'];
        }
    }
}
?>

<!-- ============================================= -->
<!-- Flash Message Toast -->
<!-- ============================================= -->
<?php if (session()->getFlashdata('success')): ?>
    <div id="toast-success" class="fixed top-24 right-8 z-50 bg-white border-l-4 border-emerald-500 rounded-xl shadow-xl px-6 py-4 flex items-center gap-4 transform transition-all duration-300 translate-y-0 opacity-100">
        <div class="bg-emerald-100 p-2 rounded-full">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <h4 class="text-gray-900 font-semibold text-sm">Berhasil!</h4>
            <p class="text-gray-500 text-sm"><?= session()->getFlashdata('success') ?></p>
        </div>
        <button onclick="document.getElementById('toast-success').style.display='none'" class="ml-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.classList.add('opacity-0', 'translate-y-[-1rem]');
                setTimeout(() => toast.style.display = 'none', 300);
            }
        }, 3000);
    </script>
<?php endif; ?>

<!-- =============================== -->
<!-- Category Filters & Search -->
<!-- =============================== -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
    <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2 md:pb-0">
        <button onclick="filterCategory('all', this)"
                class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 bg-amber-500 text-white shadow-lg shadow-amber-500/30">
            Semua Menu
        </button>
        <?php foreach ($categories as $cat): ?>
            <button onclick="filterCategory('<?= esc($cat) ?>', this)"
                    class="category-btn whitespace-nowrap px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 bg-white text-gray-600 hover:bg-amber-50 hover:text-amber-600 border border-gray-200">
                <?= esc($cat) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="relative w-full md:w-80 flex-shrink-0">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
        </span>
        <input type="text"
               id="searchInput"
               onkeyup="searchMenu()"
               placeholder="Cari menu..."
               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors shadow-sm">
    </div>
</div>

<!-- ================================ -->
<!-- Menu Grid -->
<!-- ================================ -->
<div id="menuGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if (empty($menus)): ?>
        <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Menu</h3>
            <p class="text-gray-500 text-sm">Saat ini belum ada menu yang tersedia.</p>
        </div>
    <?php else: ?>
        <?php foreach ($menus as $menu): ?>
            <?php
                $imgUrl = !empty($menu['image_path'])
                    ? base_url('uploads/' . $menu['image_path'])
                    : 'https://ui-avatars.com/api/?name=' . urlencode($menu['name']) . '&background=fef3c7&color=d97706&size=400';
            ?>
            <!-- Card -->
            <div class="menu-card group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col"
                 data-category="<?= esc($menu['category_name'] ?? '') ?>"
                 data-name="<?= esc(strtolower($menu['name'])) ?>">
                
                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                    <img src="<?= $imgUrl ?>"
                         alt="<?= esc($menu['name']) ?>"
                         class="w-full h-full object-cover"
                         loading="lazy"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($menu['name']) ?>&background=fef3c7&color=d97706&size=400'">
                    
                    <?php if (empty($menu['is_available'])): ?>
                        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="bg-white text-gray-900 text-xs font-bold px-4 py-2 rounded-full shadow-lg uppercase tracking-wider">
                                Habis Terjual
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <h3 class="text-base font-bold text-gray-900 leading-tight line-clamp-2">
                            <?= esc($menu['name']) ?>
                        </h3>
                    </div>
                    
                    <p class="text-amber-500 font-bold text-lg mb-5">
                        Rp <?= number_format($menu['price'], 0, ',', '.') ?>
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <?php if (!empty($menu['is_available'])): ?>
                            <form action="/cart/insert" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= esc($menu['id']) ?>">
                                <input type="hidden" name="product_id" value="<?= esc($menu['id']) ?>">
                                <input type="hidden" name="name" value="<?= esc($menu['name']) ?>">
                                <input type="hidden" name="price" value="<?= esc($menu['price']) ?>">
                                <input type="hidden" name="image_path" value="<?= esc($menu['image_path']) ?>">
                                <button type="submit"
                                        class="w-full py-3 rounded-xl text-sm font-semibold transition-all duration-300 bg-gray-900 text-white hover:bg-amber-500 hover:shadow-lg hover:shadow-amber-500/30 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled
                                    class="w-full py-3 rounded-xl text-sm font-semibold bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                                Tidak Tersedia
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function filterCategory(category, btn) {
        const cards = document.querySelectorAll('.menu-card');
        const searchInput = document.getElementById('searchInput');

        if (searchInput) searchInput.value = '';

        cards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        document.querySelectorAll('.category-btn').forEach(b => {
            b.classList.remove('bg-amber-500', 'text-white', 'shadow-lg', 'shadow-amber-500/30');
            b.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
            b.classList.remove('border-transparent');
        });
        
        btn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
        btn.classList.add('bg-amber-500', 'text-white', 'shadow-lg', 'shadow-amber-500/30', 'border-transparent');
    }

    function searchMenu() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-card');

        document.querySelectorAll('.category-btn').forEach((b, i) => {
            b.classList.remove('bg-amber-500', 'text-white', 'shadow-lg', 'shadow-amber-500/30');
            b.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
            if (i === 0) {
                b.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
                b.classList.add('bg-amber-500', 'text-white', 'shadow-lg', 'shadow-amber-500/30', 'border-transparent');
            }
        });

        cards.forEach(card => {
            const name = card.dataset.name || '';
            card.style.display = name.includes(query) ? '' : 'none';
        });
    }
</script>
<?= $this->endSection() ?>
