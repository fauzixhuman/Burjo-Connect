<?= $this->extend('layouts/customer') ?>

<?= $this->section('title') ?>Pesanan Diterima #<?= esc($order['id']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden text-center">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-16 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 blur-3xl rounded-full translate-y-1/2 scale-150"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-green-500 shadow-xl shadow-green-900/20 mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black mb-2 tracking-tight">Pesanan Berhasil!</h1>
                <p class="text-green-50 text-lg font-medium">Terima kasih telah memesan di Burjo Connect.</p>
            </div>
        </div>

        <!-- Order Info -->
        <div class="p-8 sm:p-12 relative">
            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Nomor Pesanan Anda</p>
            <div class="text-5xl sm:text-7xl font-black text-gray-900 tracking-tighter mb-8">
                #<?= str_pad(esc($order['id']), 4, '0', STR_PAD_LEFT) ?>
            </div>
            
            <div class="inline-block bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-10 w-full max-w-sm">
                <p class="text-gray-500 mb-1 text-sm">Total Tagihan</p>
                <p class="text-3xl font-bold text-gray-900">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
            </div>

            <!-- Instructions -->
            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100 text-left flex gap-4 max-w-xl mx-auto mb-10">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-amber-900 mb-1 text-lg">Langkah Selanjutnya</h3>
                    <p class="text-amber-800/80 leading-relaxed text-sm">
                        Silakan menuju ke kasir dan tunjukkan <strong>Nomor Pesanan</strong> di atas untuk melakukan pembayaran. Pesanan Anda akan segera diproses setelah pembayaran lunas.
                    </p>
                </div>
            </div>
            
            <div class="text-center">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 hover:bg-gray-800 text-white text-base font-bold rounded-full transition-all active:scale-95 shadow-xl shadow-gray-900/20">
                    Selesai & Kembali ke Menu
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
