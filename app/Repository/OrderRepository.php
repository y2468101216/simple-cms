<?php

namespace App\Repository;

use App\Model\User;
use App\Model\Order;
use App\Model\Product;
use Carbon\Carbon;
use App\Repository\ProductRepository;

class OrderRepository
{
    public function compelete(User $user, $inputOrder)
    {
        \DB::beginTransaction();

        $nextId = Order::count() + 1;
        $order = new Order;

        $productRespoitory = new ProductRepository;

        $isSuccess = $productRespoitory->computeProduct($inputOrder);

        if (!$isSuccess) {
            return false;
        }

        $order->user_id = $user->id;
        $order->serial = Carbon::now()->format('YmdHis').$nextId;
        $order->status = Order::COMPELETE;
        $order->paymethod = Order::CREDITCARD;
        $order->amount = $this->computeAmount($inputOrder);

        $order->save();

        \DB::commit();

        return $order;
    }

    public function computeAmount($inputOrder)
    {
        $amount = 0;

        foreach ($inputOrder as $key => $item) {
            $product = Product::where('serial', $key)->first();
            $amount += $product->amount * $item['quantity'];
        }

        return $amount;
    }
}
