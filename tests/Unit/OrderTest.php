<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repository\OrderRepository;
use App\Model\Product;
use App\Model\User;
use App\Model\Order;
use Carbon\Carbon;

class OrderTest extends TestCase
{
     use DatabaseTransactions;

     const USERID = 1;
     const PRODUCTID = 1;
     const QTY = 1;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompeleteUseVirtualAccount()
    {
        $productTest = Product::find(static::PRODUCTID);

        $products = [
            $productTest->serial => ['quantity' => static::QTY]
        ];

        $user = User::find(static::USERID);

        $repository = new OrderRepository;
        $method = config('order.paymethod.virtual_account');
        $order = $repository->create($user, $products, $method);

        $actual = empty($order->virtual_account);
        $expect = false;
        
        $this->assertEquals($actual, $expect);
    }

    public function testComputeAmount()
    {
        $productTest = Product::find(static::PRODUCTID);

        $products = [
            $productTest->serial => ['quantity' => static::QTY],
        ];

        $repository = new OrderRepository;
        $actual = $repository->computeAmount($products);

        $expect = 0;
        $expect += $products[$productTest->serial]['quantity'] * $productTest->amount;
        
        $this->assertEquals($actual, $expect);
    }

    public function testCompelete()
    {
        $nextId = Order::count() + 1;
        $order = new order;
        $order->user_id = static::USERID;
        $order->serial = Carbon::now()->format('YmdHis').$nextId;
        $order->status = config('order.status.wait_paid');
        $order->paymethod = config('order.paymethod.virtual_account');
        $order->amount = 1234;

        $repository = new OrderRepository;
        $actual = $repository->compelete($order);
        $expect = config('order.status.compelete');

        $this->assertEquals($actual, $expect);
    }
}
