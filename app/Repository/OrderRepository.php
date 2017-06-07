<?php

namespace App\Repository;

use App\Model\User;
use App\Model\Order;
use App\Model\Product;
use Carbon\Carbon;
use App\Repository\ProductRepository;
use App\Mail\Order\Complete;
use App\Mail\Order\Cancel;
use App\Mail\Order\WaitPaid;
use App\Mail\Order\CompleteError;

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

        $this->waitPaid($order);

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

    public function complete(Order $order) : string
    {
        $order->status = config('order.status.complete');
        $order->save();

        \Mail::to($order->user)->queue(new Complete($order));

        return config('order.status.complete');
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

    public function waitPaid(Order $order) : string
    {
        if ($order->paymethod == config('order.paymethod.credit_card')) {
            return config('order.status.wait_paid');
        }

        \Mail::to($order->user)->queue(new WaitPaid($order));

        return config('order.status.wait_paid');
    }

    public function completeError(Order $order) : string
    {
        $order->status = config('order.status.complete_error');
        $order->save();

        \Mail::to($order->user)->queue(new CompleteError($order));

        return config('order.status.complete_error');
    }

    public function handleCallback(String $serial, String $status) : bool
    {
        if (empty($serial)) {
            return false;
        }

        $order = Order::where('serial', $serial)->first();

        if (empty($order)) {
            return false;
        }

        if ($status != 'success') {
            return false;
        }

        $this->complete($order);

        return true;
    }
}
