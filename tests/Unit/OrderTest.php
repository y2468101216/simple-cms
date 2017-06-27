<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repository\OrderRepository;
use App\Model\Product;
use App\Model\User;
use App\Model\Order;
use App\Model\OrderProduct;
use Carbon\Carbon;
use App\Service\GithubService;
use App\Service\GoogleService;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    const USERID = 1;
    const PRODUCTID = 1;
    const QTY = 1;
    const STATUS = 'success';
     
    public $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository;
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompleteUseVirtualAccount()
    {
        $productTest = Product::find(static::PRODUCTID);

        $products = [
            $productTest->id => ['quantity' => static::QTY]
        ];

        $user = User::find(static::USERID);

        
        $method = config('order.paymethod.virtual_account');
        $order = $this->orderRepository->create($user, $products, $method);

        $actual = empty($order->virtual_account);
        $expect = false;
        
        $this->assertEquals($actual, $expect);
    }

    public function testComputeAmount()
    {
        $productTest = Product::find(static::PRODUCTID);

        $products = [
            $productTest->id => ['quantity' => static::QTY],
        ];

        
        $actual = $this->orderRepository->computeAmount($products);

        $expect = 0;
        $expect += $products[$productTest->id]['quantity'] * $productTest->amount;
        
        $this->assertEquals($actual, $expect);
    }

    public function testComplete()
    {
        
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->status = config('order.status.wait_paid');
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->amount = 1234;

        
        $actual = $this->orderRepository->complete($order);
        $expect = config('order.status.complete');

        $this->assertEquals($actual, $expect);
    }

    public function testCancel()
    {
        
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->status = config('order.status.wait_paid');
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->amount = 1234;

        $order->save();
        $order = $order->fresh();

        $orderProduct = new OrderProduct;
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = static::PRODUCTID;
        $orderProduct->quantity = static::QTY;

        $orderProduct->save();

        
        $actual = $this->orderRepository->cancel($order);
        $expect = config('order.status.cancel');

        $this->assertEquals($actual, $expect);
    }

    public function testWaitPaid()
    {
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->status = config('order.status.wait_paid');
        $order->amount = 1234;

        $order->save();

        $actual = $this->orderRepository->waitPaid($order);
        $expect = config('order.status.wait_paid');

        $this->assertEquals($actual, $expect);
    }

    public function testCompleteError()
    {
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->status = config('order.status.wait_paid');
        $order->amount = 1234;

        $order->save();

        $actual = $this->orderRepository->completeError($order);
        $expect = config('order.status.complete_error');

        $this->assertEquals($actual, $expect);
    }

    public function testGithubHandleCallback()
    {
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->status = config('order.status.wait_paid');
        $order->amount = 1234;

        $order->save();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <res>
                <serial>'.$order->serial.'</serial>
                <amount>'.$order->amount.'</amount>
                <status>'.static::STATUS.'</status>
            </res>
        ';

        $service = new GithubService($xml);

        $actual = $this->orderRepository->handleCallback($service);
        $expect = true;

        $this->assertEquals($actual, $expect);
    }

    public function testGoogleHandleCallback()
    {
        $order = new Order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$order->getNextId();
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->status = config('order.status.wait_paid');
        $order->amount = 1234;

        $order->save();

        $data = ['serial' => $order->serial, 'amount' => $order->amount, 'status' => static::STATUS];

        $service = new GoogleService($data);

        $actual = $this->orderRepository->handleCallback($service);
        $expect = true;

        $this->assertEquals($actual, $expect);
    }
}
