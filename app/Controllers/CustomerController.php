<?php

namespace App\Controllers;

use App\Models\OrderModel;

class CustomerController extends BaseController
{
    /**
     * Halaman menu utama Burjo Connect
     */
    public function menu()
    {
        $menuModel = new \App\Models\MenuModel();
        // Ambil semua menu beserta nama kategorinya
        $menus = $menuModel->select('menus.*, categories.name as category_name')
                           ->join('categories', 'categories.id = menus.category_id')
                           ->findAll();

        // Ambil data cart dari library GitHub
        $cart = \Config\Services::cart();

        $data = [
            'title'         => 'Menu - Burjo Connect',
            'pageTitle'     => 'Katalog Menu',
            'activePage'    => 'menu',
            'menus'         => $menus,
            'cartItemCount' => $cart->totalItems(),
            'cartTotal'     => $cart->total(),
        ];
        return view('customer/menu', $data);
    }

    /**
     * Halaman lacak pesanan
     */
    public function track($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        $data = [
            'title'      => 'Lacak Pesanan #' . str_pad($orderId, 4, '0', STR_PAD_LEFT),
            'pageTitle'  => 'Lacak Pesanan',
            'activePage' => 'track',
            'order'      => $order
        ];

        return view('customer/track', $data);
    }
}
