<?php

namespace App\Repository;

use App\Model\User;
use App\Model\Order;
use App\Model\Product;
use Carbon\Carbon;
use App\Repository\ProductRepository;
use App\Mail\Order\Compelete;
use App\Mail\Order\Cancel;

class OrderRepository
{
    public function create(User $user, $products, $method) : Order
    {
        \DB::beginTransaction();

        
        $order = new Order;

        $productRespoitory = new ProductRepository;

        $isSuccess = $productRespoitory->subProducts($products);

        if (!$isSuccess) {
            return false;
        }

        $order->user_id = $user->id;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
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
            $product = Product::find($key);
            $amount += $product->amount * $item['quantity'];
        }

        return $amount;
    }

    public function createVirtualAccont() : string
    {
        return 'abc123';
    }

    public function compelete(Order $order) : string
    {
        $order->status = config('order.status.compelete');
        $order->save();

        \Mail::to($order->user)->queue(new Compelete($order));

        return config('order.status.compelete');
    }

    public function cancel(Order $order) : string
    {
        $order->status = config('order.status.cancel');
        $order->save();

        $productRespoitory = new ProductRepository;
        $orderProducts = $order->orderProducts()->get();

        $products = [];
        foreach ($orderProducts as $row) {
            $products[$row->product_id] = $row->quantity;
        }

        $isSuccess = $productRespoitory->addProducts($products);

        \Mail::to($order->user)->queue(new Cancel($order));

        return config('order.status.cancel');
    }
}
