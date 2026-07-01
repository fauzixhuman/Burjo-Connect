<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class CheckoutController extends BaseController
{
    public function getOrderStatus($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return $this->response->setJSON(['error' => 'Not found'])->setStatusCode(404);
        }

        return $this->response->setJSON(['status' => $order['status']]);
    }
}
