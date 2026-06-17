<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<!-- Add Menu Button -->
<div class="mb-8 flex justify-end">
    <button onclick="document.getElementById('add-menu-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah Menu Baru
    </button>
</div>

<!-- Menu Table -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-10">
    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Katalog Menu</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50 text-left">
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Gambar</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Menu</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Kategori</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Harga</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($menus as $menu): ?>
                <tr class="table-row">
                    <td class="px-8 py-5">
                        <?php if (!empty($menu['image_path'])): ?>
                            <img src="<?= base_url('uploads/' . $menu['image_path']) ?>" alt="<?= esc($menu['name']) ?>" class="w-14 h-14 object-cover rounded-lg border border-gray-200">
                        <?php else: ?>
                            <div class="w-14 h-14 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900"><?= esc($menu['name']) ?></span>
                            <?php if (!empty($menu['description'])): ?>
                            <span class="text-xs font-medium text-gray-400 mt-0.5 truncate max-w-[200px]"><?= esc($menu['description']) ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200"><?= esc($menu['category_name']) ?></span>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-gray-900">Rp <?= number_format($menu['price'], 0, ',', '.') ?></td>
                    <td class="px-8 py-5">
                        <?php if ($menu['is_available']): ?>
                            <span class="status-badge status-ready">Tersedia</span>
                        <?php else: ?>
                            <span class="status-badge status-completed">Habis</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($menu)) ?>)" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                            <form method="post" action="<?= base_url('admin/menus/delete/' . $menu['id']) ?>" onsubmit="return confirm('Yakin ingin menghapus menu ini?')" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Menu Modal -->
<div id="add-menu-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black/40" onclick="document.getElementById('add-menu-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Tambah Menu Baru</h3>
            <button onclick="document.getElementById('add-menu-modal').classList.add('hidden')" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form method="post" action="<?= base_url('admin/menus/create') ?>" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Contoh: Indomie Goreng Spesial">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="15000">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Menu</label>
                <div class="relative">
                    <input type="file" name="image" accept="image/*" id="add-image-input" onchange="previewImage(this, 'add-image-preview')" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                </div>
                <div id="add-image-preview" class="mt-2 hidden">
                    <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <textarea name="description" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none" placeholder="Deskripsi singkat menu..."></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_available" value="1" checked id="add-available" class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                <label for="add-available" class="text-sm font-medium text-gray-700">Tersedia</label>
            </div>
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm py-3 rounded-xl transition-colors">
                Simpan Menu
            </button>
        </form>
    </div>
</div>

<!-- Edit Menu Modal -->
<div id="edit-menu-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black/40" onclick="document.getElementById('edit-menu-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Edit Menu</h3>
            <button onclick="document.getElementById('edit-menu-modal').classList.add('hidden')" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="edit-menu-form" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="name" id="edit-name" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="edit-category" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" id="edit-price" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Menu</label>
                <div id="edit-current-image" class="mb-2 hidden">
                    <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                    <img id="edit-current-img" src="" alt="Current" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                </div>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'edit-image-preview')" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti gambar</p>
                <div id="edit-image-preview" class="mt-2 hidden">
                    <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <textarea name="description" id="edit-description" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_available" value="1" id="edit-available" class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                <label for="edit-available" class="text-sm font-medium text-gray-700">Tersedia</label>
            </div>
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm py-3 rounded-xl transition-colors">
                Perbarui Menu
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(menu) {
    document.getElementById('edit-menu-form').action = '/admin/menus/update/' + menu.id;
    document.getElementById('edit-name').value = menu.name;
    document.getElementById('edit-category').value = menu.category_id;
    document.getElementById('edit-price').value = menu.price;
    document.getElementById('edit-description').value = menu.description || '';
    document.getElementById('edit-available').checked = menu.is_available == 1;

    // Tampilkan gambar saat ini jika ada
    const currentImageDiv = document.getElementById('edit-current-image');
    const currentImg = document.getElementById('edit-current-img');
    if (menu.image_path) {
        currentImg.src = '/uploads/' + menu.image_path;
        currentImageDiv.classList.remove('hidden');
    } else {
        currentImageDiv.classList.add('hidden');
    }

    // Reset preview
    document.getElementById('edit-image-preview').classList.add('hidden');

    document.getElementById('edit-menu-modal').classList.remove('hidden');
}

function previewImage(input, previewId) {
    const previewDiv = document.getElementById(previewId);
    const img = previewDiv.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            previewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        previewDiv.classList.add('hidden');
    }
}
</script>

<?= $this->endSection() ?>
