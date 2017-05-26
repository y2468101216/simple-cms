<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\repository\OrderRepository;
use App\Model\Product;
use App\Model\User;
use App\Model\Order;

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
    public function testCompelete()
    {
        $productTest = Product::find(static::PRODUCTID);

        $inputOrder = [
            $productTest->serial => ['quantity' => static::QTY]
        ];

        $user = User::find(static::USERID);

        $repository = new OrderRepository;
        $order = $repository->compelete($user, $inputOrder);

        $actual = $order->status;
        $expect = Order::COMPELETE;
        
        $this->assertEquals($actual, $expect);
    }

    public function testComputeAmount()
    {
        $productTest = Product::find(static::PRODUCTID);

        $inputOrder = [
            $productTest->serial => ['quantity' => static::QTY],
        ];

        $repository = new OrderRepository;
        $actual = $repository->computeAmount($inputOrder);

        $expect = 0;
        $expect += $inputOrder[$productTest->serial]['quantity'] * $productTest->amount;
        
        $this->assertEquals($actual, $expect);
    }
}
