<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- Total Pesanan Hari Ini -->
    <div class="stat-card bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-wider">Hari Ini</span>
        </div>
        <p class="text-4xl font-extrabold text-gray-900 mb-1 tracking-tight"><?= $totalOrders ?></p>
        <p class="text-sm text-gray-500 font-medium">Total Pesanan</p>
    </div>

    <!-- Total Pendapatan -->
    <div class="stat-card bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-wider">Hari Ini</span>
        </div>
        <p class="text-4xl font-extrabold text-gray-900 mb-1 tracking-tight">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
        <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
    </div>

    <!-- Menu Aktif -->
    <div class="stat-card bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wider">Tersedia</span>
        </div>
        <p class="text-4xl font-extrabold text-gray-900 mb-1 tracking-tight"><?= $totalMenus ?></p>
        <p class="text-sm text-gray-500 font-medium">Menu Aktif</p>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-10">
    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Pesanan Terbaru</h3>
        <a href="<?= base_url('admin/orders') ?>" class="text-sm font-bold text-amber-500 hover:text-amber-600 transition-colors">Lihat Semua</a>
    </div>

    <?php if (empty($recentOrders)): ?>
        <div class="px-8 py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <p class="text-sm text-gray-400 font-medium">Belum ada pesanan hari ini</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-left">
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">No. Order</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Meja</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($recentOrders as $order): ?>
                    <tr class="table-row">
                        <td class="px-8 py-5 text-sm font-bold text-gray-900">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-8 py-5 text-sm font-medium text-gray-500">Meja <?= esc($order['table_number']) ?></td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-900">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col items-start gap-1.5">
                                <span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                                <?php if ($order['is_lunas']): ?>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-widest">Lunas</span>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded-full uppercase tracking-widest">Belum Lunas</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm font-medium text-gray-400"><?= date('H:i', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto Update Dashboard setiap 10 detik
    setInterval(() => {
        window.location.reload();
    }, 10000);
</script>
<?= $this->endSection() ?>
