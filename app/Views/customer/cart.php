<?= $this->extend('layouts/customer') ?>

<?= $this->section('title') ?>Keranjang Belanja<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Kontainer Utama: Lebih sempit (max-w-4xl) agar tidak terlalu melebar, margin atas-bawah diperbesar -->
<div class="max-w-5xl mx-auto py-12 px-6 lg:px-8">
    
    <div class="mb-12 border-b border-gray-100 pb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">Keranjang Anda</h1>
            <p class="text-gray-500 mt-2 text-base">Cek kembali pesanan Anda sebelum melanjutkan ke pembayaran.</p>
        </div>
        <?php if (!empty($cartItems)): ?>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('/cart/destroy') ?>" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-5 py-2 rounded-full text-sm font-bold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Kosongkan Keranjang
                </a>
                <span class="bg-gray-100 text-gray-600 text-sm font-semibold px-5 py-2 rounded-full inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <?= esc($totalItems) ?> Menu
                </span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($cartItems)): ?>
        <!-- Empty State: Dibuat super lega tanpa border kaku -->
        <div class="py-32 flex flex-col items-center justify-center text-center max-w-2xl mx-auto">
            <div class="w-48 h-48 mb-10 flex items-center justify-center rounded-full bg-gray-50/50">
                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Keranjang masih kosong</h2>
            <p class="text-gray-500 text-lg mb-10 leading-relaxed">Sepertinya Anda belum memilih hidangan apapun. Yuk, jelajahi menu kami dan temukan makanan favorit Anda hari ini!</p>
            <a href="<?= base_url('/') ?>" class="inline-block w-max px-10 py-4 text-base font-bold rounded-full text-white bg-amber-500 hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/20">
                Lihat Katalog Menu
            </a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-16">
            <!-- Left Side: Items List -->
            <div class="flex-1">
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-100">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="flex py-10 group">
                                <!-- Item Image -->
                                <div class="h-28 w-28 flex-shrink-0 overflow-hidden rounded-2xl bg-gray-50 border border-gray-100">
                                    <?php
                                        $imgSrc = !empty($item['image']) 
                                            ? base_url('uploads/' . $item['image']) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($item['name']) . '&background=fef3c7&color=d97706&size=200'; 
                                    ?>
                                    <img src="<?= $imgSrc ?>" alt="<?= esc($item['name']) ?>" class="h-full w-full object-cover object-center">
                                </div>

                                <div class="ml-8 flex flex-1 flex-col justify-center">
                                    <div>
                                        <div class="flex justify-between items-start text-gray-900">
                                            <div>
                                                <h3 class="text-xl font-bold mb-1"><?= esc($item['name']) ?></h3>
                                                <p class="text-sm text-gray-500">Rp <?= number_format($item['price'], 0, ',', '.') ?> / porsi</p>
                                            </div>
                                            <p class="text-lg font-bold text-gray-900 ml-4">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-6">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-4 bg-gray-50/50 rounded-full p-1 border border-gray-100">
                                            <form action="<?= base_url('cart/update') ?>" method="POST" class="m-0">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="rowid" value="<?= esc($item['rowid']) ?>">
                                                <input type="hidden" name="qty" value="<?= max(0, $item['qty'] - 1) ?>">
                                                <button type="submit" <?= $item['qty'] <= 0 ? 'disabled' : '' ?> class="w-9 h-9 rounded-full flex items-center justify-center bg-white text-gray-500 hover:text-amber-600 shadow-sm transition-colors border border-gray-100 <?= $item['qty'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path></svg>
                                                </button>
                                            </form>
                                            
                                            <span class="w-6 text-center font-bold text-gray-900 text-sm"><?= esc($item['qty']) ?></span>
                                            
                                            <form action="<?= base_url('cart/update') ?>" method="POST" class="m-0">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="rowid" value="<?= esc($item['rowid']) ?>">
                                                <input type="hidden" name="qty" value="<?= $item['qty'] + 1 ?>">
                                                <button type="submit" class="w-9 h-9 rounded-full flex items-center justify-center bg-white text-gray-500 hover:text-amber-600 shadow-sm transition-colors border border-gray-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </form>
                                        </div>

                                        <form action="<?= base_url('cart/remove/' . esc($item['rowid'])) ?>" method="POST" class="m-0">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 transition-colors px-4 py-2 rounded-full">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Right Side: Summary -->
            <div class="w-full lg:w-[380px]">
                <div class="bg-gray-50/50 rounded-3xl border border-gray-100 p-8 sticky top-28">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan</h2>
                    
                    <dl class="space-y-4 text-sm text-gray-600">
                        <div class="flex items-center justify-between">
                            <dt>Subtotal</dt>
                            <dd class="font-medium text-gray-900">Rp <?= number_format($total, 0, ',', '.') ?></dd>
                        </div>
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200/60">
                            <dt>Biaya Layanan</dt>
                            <dd class="font-medium text-green-600">Gratis</dd>
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <dt class="text-base font-bold text-gray-900">Total Harga</dt>
                            <dd class="text-2xl font-black text-gray-900">Rp <?= number_format($total, 0, ',', '.') ?></dd>
                        </div>
                    </dl>

                    <form action="<?= base_url('cart/checkout') ?>" method="POST" class="mt-8">
                        <?= csrf_field() ?>
                        <input type="hidden" name="payment_method" value="cash">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-4 text-base font-bold rounded-2xl text-white bg-gray-900 hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-xl shadow-gray-900/20">
                            Proses Checkout
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </form>
                    
                    <p class="mt-6 text-center text-xs text-gray-500 leading-relaxed">
                        Pembayaran akan dilakukan secara tunai di kasir setelah pesanan dikonfirmasi.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
