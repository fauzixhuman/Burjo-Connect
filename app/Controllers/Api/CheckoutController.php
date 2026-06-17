<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class CheckoutController extends ResourceController
{
    protected $format = 'json';

    public function getMenus()
    {
        $menuModel = new MenuModel();
        $menus = $menuModel->where('is_available', true)->findAll();
        
        $cartItems = [];
        foreach ($menus as $menu) {
            $cartItems[] = [
                'id'     => (int)$menu['id'],
                'name'   => $menu['name'],
                'price'  => (float)$menu['price'],
                'qty'    => 1, // Default kuantitas untuk keperluan demo
                'status' => 'unpaid',
            ];
        }

        return $this->respond($cartItems);
    }

    public function processCheckout()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->items) || !isset($json->paymentMode) || !isset($json->total)) {
            return $this->failValidationErrors('Invalid payload');
        }

        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $transactionModel = new \App\Models\TransactionModel();
        $db = \Config\Database::connect();

        $db->transStart();

        $sessionId = $json->sessionId ?? null;
        $paymentMethod = $json->paymentMethod ?? 'qris';
        $paymentMode = $json->paymentMode; // 'single' atau 'split'

        // Cari order group berdasarkan session_id, atau buat baru
        $order = null;
        if ($sessionId) {
            $order = $orderModel->where('session_id', $sessionId)->first();
        }

        if (!$order) {
            $orderId = $orderModel->insert([
                'session_id'   => $sessionId,
                'table_number' => $json->tableNumber ?? '1',
                'status'       => 'pending',
                'total_amount' => 0, // Akan diupdate
            ]);
        } else {
            $orderId = $order['id'];
        }

        // Buat transaksi
        $transactionStatus = ($paymentMethod === 'cash') ? 'unpaid' : 'paid';
        $transactionId = $transactionModel->insert([
            'order_id'       => $orderId,
            'payment_method' => $paymentMethod,
            'payment_mode'   => $paymentMode,
            'amount'         => $json->total,
            'status'         => $transactionStatus,
        ]);

        $selectedItems = $json->selectedItems ?? [];
        $addedTotal = 0;

        // Masukkan detail menu ke Order Items
        foreach ($json->items as $item) {
            if ($item->status === 'paid' && !in_array($item->id, $selectedItems)) {
                // Dalam skenario split bill, item yang sudah lunas sebelumnya tidak dikirim ulang.
                continue; 
            }

            $isBeingPaid = false;
            if ($paymentMode === 'single') {
                if ($item->status !== 'paid') {
                    $isBeingPaid = true;
                }
            } else if ($paymentMode === 'split' && in_array($item->id, $selectedItems)) {
                $isBeingPaid = true;
            }

            // Cek apakah item ini sudah ada di database (sebagai unpaid)
            $existingItem = $orderItemModel->where('order_id', $orderId)
                                           ->where('menu_id', $item->id)
                                           ->where('payment_status', 'unpaid')
                                           ->first();

            if ($isBeingPaid) {
                if ($existingItem) {
                    $orderItemModel->update($existingItem['id'], [
                        'transaction_id' => $transactionId,
                        'payment_status' => $transactionStatus,
                    ]);
                } else {
                    $orderItemModel->insert([
                        'order_id'       => $orderId,
                        'transaction_id' => $transactionId,
                        'menu_id'        => $item->id,
                        'qty'            => $item->qty,
                        'price_at_order' => $item->price,
                        'payment_status' => $transactionStatus,
                    ]);
                }
                $addedTotal += ($item->price * $item->qty);
            } else {
                if (!$existingItem) {
                    $orderItemModel->insert([
                        'order_id'       => $orderId,
                        'transaction_id' => null,
                        'menu_id'        => $item->id,
                        'qty'            => $item->qty,
                        'price_at_order' => $item->price,
                        'payment_status' => 'unpaid',
                    ]);
                }
            }
        }

        // Update total_amount pesanan utama
        if ($order) {
            $newTotal = $order['total_amount'] + $addedTotal;
            $orderModel->update($orderId, ['total_amount' => $newTotal]);
        } else {
            $orderModel->update($orderId, ['total_amount' => $addedTotal]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Gagal memproses transaksi.');
        }

        return $this->respondCreated([
            'message' => 'Pembayaran berhasil diproses',
            'orderId' => $orderId
        ]);
    }

    public function getOrderStatus($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);
        
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }

        return $this->respond([
            'status' => $order['status']
        ]);
    }
}
