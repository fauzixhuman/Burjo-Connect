<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<!-- Orders Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-10">
    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Daftar Pesanan</h3>
    </div>
    
    <?php if (empty($orders)): ?>
        <div class="px-8 py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <p class="text-sm text-gray-400 font-medium">Belum ada pesanan yang masuk.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-left">
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">No. Order</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status Pembayaran</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($orders as $order): ?>
                    <tr class="table-row">
                        <td class="px-8 py-5 text-sm font-bold text-gray-900">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-8 py-5 text-sm font-medium text-gray-400"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-900">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                        <td class="px-8 py-5">
                            <?php if ($order['is_lunas']): ?>
                                <span class="status-badge status-ready bg-green-100 text-green-700">LUNAS</span>
                            <?php else: ?>
                                <span class="status-badge status-pending text-red-700 bg-red-100">BELUM LUNAS</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button onclick="showOrderDetail(<?= $order['id'] ?>)" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Order Detail Modal -->
<div id="order-detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black/40" onclick="closeOrderDetail()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-bold text-gray-900" id="modal-title">Detail Pesanan</h3>
            <button onclick="closeOrderDetail()" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="modal-body" class="p-6">
            <p class="text-sm text-gray-500 text-center">Memuat...</p>
        </div>
    </div>
</div>

<script>
let isModalOpen = false;

// Auto Refresh table every 30 seconds smoothly if modal is closed
setInterval(() => {
    if (!isModalOpen) {
        fetch(window.location.href, { redirect: 'manual' })
            .then(response => {
                if (response.type === 'opaqueredirect' || response.redirected) {
                    window.location.href = '<?= base_url('admin/login') ?>';
                    return null;
                }
                return response.text();
            })
            .then(html => {
                if (!html) return;
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Cek ulang apakah modal tiba-tiba terbuka saat fetch berlangsung
                if (!isModalOpen) {
                    const newTable = doc.querySelector('.bg-white.rounded-lg.border.border-gray-200.shadow-sm.overflow-hidden');
                    const oldTable = document.querySelector('.bg-white.rounded-lg.border.border-gray-200.shadow-sm.overflow-hidden');
                    
                    if (newTable && oldTable) {
                        oldTable.innerHTML = newTable.innerHTML;
                    }
                }
            })
            .catch(error => console.error('Auto-refresh error:', error));
    }
}, 30000);

function showOrderDetail(orderId) {
    isModalOpen = true;
    const modal = document.getElementById('order-detail-modal');
    const modalBody = document.getElementById('modal-body');
    const modalTitle = document.getElementById('modal-title');
    
    modal.classList.remove('hidden');
    modalBody.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">Memuat detail...</p>';

    fetch('<?= base_url('admin/orders/') ?>' + orderId + '/detail')
        .then(res => res.json())
        .then(data => {
            modalTitle.textContent = 'Grup Pesanan #' + String(data.order.id).padStart(4, '0');
            let html = '<div class="space-y-3">';
            html += '<div class="flex justify-between text-sm"><span class="text-gray-500">Pelanggan</span><span class="font-semibold">Reguler</span></div>';
            html += '<div class="flex justify-between text-sm"><span class="text-gray-500">Total Akumulasi</span><span class="font-semibold">Rp ' + Number(data.order.total_amount).toLocaleString('id-ID') + '</span></div>';
            html += '<div class="flex justify-between text-sm"><span class="text-gray-500">Status Dapur</span><span class="status-badge status-' + data.order.status + '">' + data.order.status.charAt(0).toUpperCase() + data.order.status.slice(1) + '</span></div>';
            html += '<hr class="my-4">';
            
            if (!data.transactions || data.transactions.length === 0) {
                html += '<p class="text-sm text-gray-400">Tidak ada riwayat transaksi.</p>';
            } else {
                let allItems = [];
                let hasUnpaid = false;
                let unpaidTrxId = null;
                let paymentMethodText = 'QRIS';
                
                data.transactions.forEach(trx => {
                    if (trx.status === 'unpaid') {
                        hasUnpaid = true;
                        unpaidTrxId = trx.id;
                    }
                    if (trx.payment_method === 'cash') {
                        paymentMethodText = 'TUNAI';
                    }
                    if (trx.items) {
                        trx.items.forEach(item => allItems.push(item));
                    }
                });

                let groupedItems = {};
                allItems.forEach(item => {
                    if (!groupedItems[item.menu_name]) {
                        groupedItems[item.menu_name] = { ...item, qty: 0 };
                    }
                    groupedItems[item.menu_name].qty += parseInt(item.qty);
                });
                
                const statusClass = hasUnpaid ? 'status-pending text-red-700 bg-red-100' : 'status-ready';
                const statusText = hasUnpaid ? 'BELUM LUNAS' : 'LUNAS';
                const isSplit = data.transactions.length > 1;

                html += '<div class="mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">';
                html += '  <div class="flex justify-between items-start mb-4">';
                html += '    <div><h5 class="text-sm font-bold text-gray-800">Rincian Item</h5><p class="text-[10px] text-gray-500 uppercase tracking-widest">' + (isSplit ? 'SPLIT BILL (QRIS)' : paymentMethodText) + '</p></div>';
                html += '    <div class="text-right">';
                html += '      <span class="status-badge ' + statusClass + ' mb-1 inline-block">' + statusText + '</span>';
                html += '    </div>';
                html += '  </div>';
                html += '  <div class="space-y-1">';
                
                if (Object.keys(groupedItems).length === 0) {
                     html += '    <p class="text-xs text-gray-400 italic">Data item pada versi lama tidak terhubung ke transaksi.</p>';
                } else {
                    Object.values(groupedItems).forEach(function(item) {
                        html += '    <div class="flex justify-between items-center text-sm border-t border-gray-200/50 pt-2 mt-2">';
                        html += '      <span class="text-gray-600">' + item.menu_name + ' <span class="text-xs text-gray-400 font-bold ml-1">x' + item.qty + '</span></span>';
                        html += '      <span class="font-medium">Rp ' + Number(item.price_at_order * item.qty).toLocaleString('id-ID') + '</span>';
                        html += '    </div>';
                    });
                }
                
                html += '  </div>';
                
                html += '  <div class="mt-6 pt-5 border-t border-gray-200/50 flex gap-2">';
                if (hasUnpaid && unpaidTrxId) {
                    html += '  <form method="post" action="<?= base_url('admin/transactions/') ?>' + unpaidTrxId + '/mark-paid" class="flex-1">';
                    html += '    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-colors shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Tandai Lunas</button>';
                    html += '  </form>';
                }
                html += '    <a href="<?= base_url('admin/orders/') ?>' + data.order.id + '/print" target="_blank" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors shadow-sm ' + (hasUnpaid ? 'flex-none' : 'w-full flex-1') + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>Cetak Struk</a>';
                html += '  </div>';

                html += '</div>';
            }
            html += '</div>';
            modalBody.innerHTML = html;
        })
        .catch(err => {
            modalBody.innerHTML = '<p class="text-sm text-red-500 text-center py-8">Gagal memuat detail pesanan.</p>';
        });
}

function closeOrderDetail() {
    isModalOpen = false;
    document.getElementById('order-detail-modal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
