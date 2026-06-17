<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>



<?php 
// Ekstrak kategori unik dari menu
$categories = [];
foreach ($menus as $m) {
    if (!in_array($m['category_name'], $categories)) {
        $categories[] = $m['category_name'];
    }
}
?>

<!-- Category Pills -->
<div class="px-4 py-4 overflow-x-auto whitespace-nowrap border-b border-gray-200 bg-white sticky top-0 z-30">
    <div class="flex gap-2">
        <button onclick="filterCategory('all')" id="btn-cat-all" class="category-btn px-4 py-2 rounded-full bg-yellow-500 text-white text-sm font-bold shadow-sm transition-colors">Semua</button>
        <?php foreach ($categories as $cat): ?>
            <button onclick="filterCategory('<?= esc($cat) ?>')" id="btn-cat-<?= esc($cat) ?>" class="category-btn px-4 py-2 rounded-full bg-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-200 transition-colors"><?= esc($cat) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- Search Bar -->
<div class="px-4 mt-4">
    <div class="relative">
        <input type="text" id="searchInput" onkeyup="searchMenu()" placeholder="Cari menu..." class="w-full bg-white border border-gray-300 rounded-lg py-3 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm font-medium text-gray-800 transition-all shadow-sm">
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </div>
</div>

<!-- Menu Catalog List -->
<div class="px-4 mt-6 grid grid-cols-2 gap-4 pb-28" id="menu-container">
    <?php foreach ($menus as $menu): ?>
    <div class="menu-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col <?= !$menu['is_available'] ? 'opacity-70 grayscale-[30%]' : '' ?>" data-category="<?= esc($menu['category_name']) ?>" data-name="<?= strtolower(esc($menu['name'])) ?>">
        <div class="relative w-full aspect-square bg-gray-100">
            <?php if ($menu['image_path']): ?>
                <img src="<?= base_url('uploads/' . $menu['image_path']) ?>" alt="<?= esc($menu['name']) ?>" class="object-cover w-full h-full <?= !$menu['is_available'] ? 'grayscale' : '' ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($menu['name']) ?>&background=random'">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($menu['name']) ?>&background=random" alt="<?= esc($menu['name']) ?>" class="object-cover w-full h-full <?= !$menu['is_available'] ? 'grayscale' : '' ?>">
            <?php endif; ?>
            
            <?php if (!$menu['is_available']): ?>
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <span class="bg-black/70 text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider">Habis</span>
            </div>
            <?php endif; ?>
        </div>
        <div class="p-3 flex-1 flex flex-col">
            <h3 class="font-bold text-gray-800 text-sm mb-1 leading-tight"><?= esc($menu['name']) ?></h3>
            <p class="text-yellow-600 font-bold text-sm mt-auto mb-3">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>
            
            <?php if ($menu['is_available']): ?>
                <div id="btn-container-<?= $menu['id'] ?>" data-name="<?= esc($menu['name']) ?>" data-price="<?= $menu['price'] ?>">
                    <button onclick="addToCart(<?= $menu['id'] ?>, '<?= esc(addslashes($menu['name'])) ?>', <?= $menu['price'] ?>)" class="w-full bg-yellow-100 hover:bg-yellow-500 text-yellow-700 hover:text-white font-bold py-2 rounded-lg text-sm transition-colors duration-200">
                        Tambah
                    </button>
                </div>
            <?php else: ?>
                <button disabled class="w-full bg-gray-200 text-gray-500 font-bold py-2 rounded-lg text-sm cursor-not-allowed">
                    Habis
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Floating Cart Button -->
<div class="fixed bottom-6 left-0 right-0 max-w-md mx-auto px-4 z-50 hidden" id="cart-floating-btn">
    <a href="<?= base_url('checkout?table=' . esc($table)) ?>" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white shadow-lg shadow-yellow-500/40 rounded-xl p-4 flex items-center justify-between transition-transform transform active:scale-95 block">
        <div class="flex items-center gap-3">
            <div class="relative w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow">0</span>
            </div>
            <div class="text-left">
                <p class="text-xs text-yellow-100 font-bold">Total Tagihan</p>
                <p class="font-bold text-lg leading-tight" id="cart-total">Rp 0</p>
            </div>
        </div>
        <div class="flex items-center gap-1 font-bold text-sm bg-white text-yellow-600 px-4 py-2 rounded-lg shadow-sm">
            Checkout
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </div>
    </a>
</div>

<script>
    let cart = JSON.parse(localStorage.getItem('warmindo_cart')) || {};

    // Sinkronkan harga cart dengan harga terbaru dari database (data-attribute)
    // Ini memastikan harga lama yang salah (misal 0) akan diperbarui
    function syncCartPrices() {
        document.querySelectorAll('[id^="btn-container-"]').forEach(container => {
            const id = container.id.replace('btn-container-', '');
            if (cart[id]) {
                const dbPrice = parseFloat(container.getAttribute('data-price'));
                const dbName = container.getAttribute('data-name');
                if (dbPrice > 0) {
                    cart[id].price = dbPrice;
                    cart[id].name = dbName;
                }
            }
        });
        localStorage.setItem('warmindo_cart', JSON.stringify(cart));
    }

    function saveCart() {
        localStorage.setItem('warmindo_cart', JSON.stringify(cart));
        updateCartUI();
    }

    function addToCart(id, name, price) {
        price = parseFloat(price);
        if (cart[id]) {
            cart[id].qty += 1;
            cart[id].price = price; // Selalu perbarui harga dari database
        } else {
            cart[id] = { id: id, name: name, price: price, qty: 1 };
        }
        saveCart();
    }

    function removeFromCart(id) {
        if (cart[id]) {
            cart[id].qty -= 1;
            if (cart[id].qty <= 0) {
                delete cart[id];
            }
            saveCart();
        }
    }

    function updateCartUI() {
        let totalItems = 0;
        let totalPrice = 0;

        // Update semua tombol menu
        document.querySelectorAll('[id^="btn-container-"]').forEach(container => {
            const id = container.id.replace('btn-container-', '');
            const item = cart[id];
            
            const name = container.getAttribute('data-name');
            const price = parseFloat(container.getAttribute('data-price'));
            const safeName = name ? name.replace(/'/g, "\\'") : '';

            if (item && item.qty > 0) {
                totalItems += item.qty;
                totalPrice += (item.price * item.qty);

                container.innerHTML = `
                    <div class="w-full bg-yellow-500 text-white font-bold py-1 rounded-lg text-sm flex items-center justify-between px-1 shadow-sm">
                        <button onclick="removeFromCart('${id}')" class="w-8 h-8 rounded hover:bg-yellow-600 active:scale-95 transition-transform text-lg flex items-center justify-center">-</button>
                        <span class="text-sm">${item.qty}</span>
                        <button onclick="addToCart('${id}', '${safeName}', ${price})" class="w-8 h-8 rounded hover:bg-yellow-600 active:scale-95 transition-transform text-lg flex items-center justify-center">+</button>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <button onclick="addToCart('${id}', '${safeName}', ${price})" class="w-full bg-yellow-100 hover:bg-yellow-500 text-yellow-700 hover:text-white font-bold py-2 rounded-lg text-sm transition-colors duration-200">
                        Tambah
                    </button>
                `;
            }
        });

        // Juga hitung item yang ada di cart tapi mungkin tidak ada di halaman ini
        Object.keys(cart).forEach(id => {
            const container = document.getElementById('btn-container-' + id);
            if (!container && cart[id] && cart[id].qty > 0) {
                totalItems += cart[id].qty;
                totalPrice += (cart[id].price * cart[id].qty);
            }
        });

        // Update tombol checkout mengambang
        const floatingCart = document.getElementById('cart-floating-btn');
        if (totalItems > 0) {
            floatingCart.classList.remove('hidden');
            document.getElementById('cart-count').innerText = totalItems;
            document.getElementById('cart-total').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
        } else {
            floatingCart.classList.add('hidden');
        }
    }

    function filterCategory(cat) {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-yellow-500', 'text-white', 'shadow-sm');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        
        const activeBtn = document.getElementById('btn-cat-' + cat);
        if (activeBtn) {
            activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
            activeBtn.classList.add('bg-yellow-500', 'text-white', 'shadow-sm');
        }

        document.querySelectorAll('.menu-card').forEach(card => {
            if (cat === 'all' || card.dataset.category === cat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function searchMenu() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.menu-card').forEach(card => {
            const name = card.dataset.name;
            if (name.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', () => {
        syncCartPrices(); // Perbaiki harga lama yang salah
        updateCartUI();
    });
</script>

<?= $this->endSection() ?>
