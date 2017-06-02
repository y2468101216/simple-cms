<?php

namespace App\Repository;

use App\Model\User;
use App\Model\Order;
use App\Model\Product;
use Carbon\Carbon;
use App\Repository\ProductRepository;
use App\Mail\Order\Compelete;

class OrderRepository
{
    public function create(User $user, $products, $method) : Order
    {
        \DB::beginTransaction();

        $nextId = Order::count() + 1;
        $order = new Order;

        $productRespoitory = new ProductRepository;

        $isSuccess = $productRespoitory->computeProduct($products);

        if (!$isSuccess) {
            return false;
        }

        $order->user_id = $user->id;
        $order->serial = Carbon::now()->format('YmdHis').$nextId;
        $order->status = config('order.status.wait_paid');
        $order->paymethod = $method;
        $order->amount = $this->computeAmount($products);

        if ($method == config('order.paymethod.virtual_account')) {
            $order->virtual_account = $this->createVirtualAccont();
        }

        $order->save();

        \DB::commit();

        return $order;
    }

    public function computeAmount($products) : int
    {
        $amount = 0;

        foreach ($products as $key => $item) {
            $product = Product::where('serial', $key)->first();
            $amount += $product->amount * $item['quantity'];
        }

        return $amount;
    }

    public function createVirtualAccont()
    {
        return 'abc123';
    }

    public function compelete(Order $order) : string {
        $order->status = config('order.status.compelete');
        $order->save();

        \Mail::to($order->user)->queue(new Compelete($order));

        return config('order.status.compelete');
    }
}
