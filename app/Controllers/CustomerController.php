<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;

class CustomerController extends BaseController
{
    public function menu()
    {
        $menuModel = new \App\Models\MenuModel();
        // Ambil semua menu beserta nama kategorinya
        $menus = $menuModel->select('menus.*, categories.name as category_name')
                           ->join('categories', 'categories.id = menus.category_id')
                           ->findAll();

        $data = [
            'title' => 'Menu - Warmindo Connect',
            'table' => isset($_GET['table']) ? $_GET['table'] : '1',
            'menus' => $menus
        ];
        return view('customer/menu', $data);
    }

    public function checkout()
    {
        $data = [
            'title' => 'Checkout & Split Bill',
            'table' => isset($_GET['table']) ? $_GET['table'] : '1'
        ];
        return view('customer/checkout', $data);
    }

    public function track($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        $data = [
            'title' => 'Lacak Pesanan #' . str_pad($orderId, 4, '0', STR_PAD_LEFT),
            'order' => $order
        ];

        return view('customer/track', $data);
    }
}
