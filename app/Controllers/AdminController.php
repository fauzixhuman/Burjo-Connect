<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class AdminController extends BaseController
{
    protected $menuModel;
    protected $categoryModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->menuModel      = new MenuModel();
        $this->categoryModel  = new CategoryModel();
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    // ─── Dashboard ────────────────────────────────────────────────────
    public function dashboard()
    {
        $today = date('Y-m-d');
        $recentOrders = $this->orderModel->orderBy('created_at', 'DESC')->findAll(10);
        $transactionModel = new \App\Models\TransactionModel();

        foreach ($recentOrders as &$order) {
            $unpaidItems = $this->orderItemModel->where('order_id', $order['id'])
                                                ->where('payment_status', 'unpaid')
                                                ->countAllResults();
            $paidItems = $this->orderItemModel->where('order_id', $order['id'])
                                              ->where('payment_status', 'paid')
                                              ->countAllResults();
            
            $order['is_lunas'] = ($paidItems > 0 && $unpaidItems == 0);
        }

        $data = [
            'title'       => 'Dashboard - Admin',
            'pageTitle'   => 'Dashboard',
            'activePage'  => 'dashboard',
            'totalOrders' => $this->orderModel->where('DATE(created_at)', $today)->countAllResults(false),
            'totalRevenue'=> $this->orderModel->where('DATE(created_at)', $today)->selectSum('total_amount')->first()['total_amount'] ?? 0,
            'totalMenus'  => $this->menuModel->where('is_available', true)->countAllResults(false),
            'recentOrders'=> $recentOrders,
        ];

        return view('admin/dashboard', $data);
    }

    // ─── Orders ───────────────────────────────────────────────────────
    public function orders()
    {
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        $transactionModel = new \App\Models\TransactionModel();

        foreach ($orders as &$order) {
            $unpaidItems = $this->orderItemModel->where('order_id', $order['id'])
                                                ->where('payment_status', 'unpaid')
                                                ->countAllResults();
            $paidItems = $this->orderItemModel->where('order_id', $order['id'])
                                              ->where('payment_status', 'paid')
                                              ->countAllResults();
            
            $order['is_lunas'] = ($paidItems > 0 && $unpaidItems == 0);
        }

        $data = [
            'title'      => 'Pesanan - Admin',
            'pageTitle'  => 'Pesanan Masuk',
            'activePage' => 'orders',
            'orders'     => $orders,
        ];

        return view('admin/orders', $data);
    }

    public function orderDetail($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to(base_url('admin/orders'))->with('error', 'Pesanan tidak ditemukan.');
        }

        $transactionModel = new \App\Models\TransactionModel();
        
        $transactions = $transactionModel->where('order_id', $id)->findAll();
        
        $items = $this->orderItemModel
            ->select('order_items.*, menus.name as menu_name')
            ->join('menus', 'menus.id = order_items.menu_id')
            ->where('order_id', $id)
            ->findAll();

        // Attach items to their respective transactions
        foreach ($transactions as &$trx) {
            $trx['items'] = array_filter($items, function($item) use ($trx) {
                return $item['transaction_id'] == $trx['id'];
            });
            $trx['items'] = array_values($trx['items']); // re-index
        }

        return $this->response->setJSON([
            'order' => $order,
            'transactions' => $transactions
        ]);
    }

    public function printOrder($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to(base_url('admin/orders'))->with('error', 'Pesanan tidak ditemukan.');
        }

        $transactionModel = new \App\Models\TransactionModel();
        
        $transactions = $transactionModel->where('order_id', $id)->findAll();
        
        $items = $this->orderItemModel
            ->select('order_items.*, menus.name as menu_name')
            ->join('menus', 'menus.id = order_items.menu_id')
            ->where('order_id', $id)
            ->findAll();

        $groupedItems = [];
        foreach ($items as $item) {
            if (!isset($groupedItems[$item['menu_name']])) {
                $groupedItems[$item['menu_name']] = [
                    'name' => $item['menu_name'],
                    'qty' => 0,
                    'price' => $item['price_at_order'],
                    'subtotal' => 0
                ];
            }
            $groupedItems[$item['menu_name']]['qty'] += $item['qty'];
            $groupedItems[$item['menu_name']]['subtotal'] += ($item['qty'] * $item['price_at_order']);
        }

        $paymentMethod = 'TUNAI';
        if (count($transactions) > 1) {
            $paymentMethod = 'SPLIT BILL (QRIS)';
        } elseif (count($transactions) === 1 && $transactions[0]['payment_method'] === 'qris') {
            $paymentMethod = 'QRIS';
        }

        $data = [
            'order' => $order,
            'items' => array_values($groupedItems),
            'paymentMethod' => $paymentMethod
        ];

        return view('admin/print_receipt', $data);
    }

    public function updateOrderStatus($id)
    {
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'cooking', 'ready', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->orderModel->update($id, ['status' => $status]);
        return redirect()->to(base_url('admin/orders'))->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function markTransactionPaid($transactionId)
    {
        $transactionModel = new \App\Models\TransactionModel();
        
        $trx = $transactionModel->find($transactionId);
        if (!$trx) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $transactionModel->update($transactionId, ['status' => 'paid']);

        // Update payment_status on order_items too based on the associated order_id
        $this->orderItemModel->where('order_id', $trx['order_id'])
                             ->set(['payment_status' => 'paid'])
                             ->update();

        return redirect()->to(base_url('admin/orders'))->with('success', 'Transaksi berhasil ditandai Lunas.');
    }

    // ─── Menu Management ─────────────────────────────────────────────
    public function menus()
    {
        $data = [
            'title'      => 'Katalog Menu - Admin',
            'pageTitle'  => 'Manajemen Menu',
            'activePage' => 'menus',
            'menus'      => $this->menuModel
                                ->select('menus.*, categories.name as category_name')
                                ->join('categories', 'categories.id = menus.category_id')
                                ->findAll(),
            'categories' => $this->categoryModel->groupBy('name')->findAll(),
        ];

        return view('admin/menus', $data);
    }

    public function createMenu()
    {
        $data = [
            'category_id'  => $this->request->getPost('category_id'),
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available') ? true : false,
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $newName);
            $data['image_path'] = $newName;
        }

        $this->menuModel->insert($data);

        return redirect()->to(base_url('admin/menus'))->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function updateMenu($id)
    {
        $data = [
            'category_id'  => $this->request->getPost('category_id'),
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available') ? true : false,
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Hapus gambar lama jika ada
            $oldMenu = $this->menuModel->find($id);
            if ($oldMenu && !empty($oldMenu['image_path'])) {
                $oldPath = FCPATH . 'uploads/' . $oldMenu['image_path'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $newName);
            $data['image_path'] = $newName;
        }

        $this->menuModel->update($id, $data);

        return redirect()->to(base_url('admin/menus'))->with('success', 'Menu berhasil diperbarui!');
    }

    public function deleteMenu($id)
    {
        // Hapus gambar jika ada
        $menu = $this->menuModel->find($id);
        if ($menu && !empty($menu['image_path'])) {
            $imagePath = FCPATH . 'uploads/' . $menu['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->menuModel->delete($id);
        return redirect()->to(base_url('admin/menus'))->with('success', 'Menu berhasil dihapus!');
    }
}
