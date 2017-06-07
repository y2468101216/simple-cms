<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Model\User;
use App\Model\Product;
use App\Model\Order;
use App\Model\OrderProduct;
use Carbon\Carbon;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    const USERID = 1;
    const PRODUCTID = 1;
    const QTY = 1;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreUseVirtualAccount()
    {
        
        $productTest = Product::find(static::PRODUCTID);

        $prodcuts = [
            $productTest->id => ['quantity' => static::QTY]
        ];

        $params = [
            'products' => $prodcuts,
            'method' => config('order.paymethod.virtual_account')
        ];

        $response = $this->actingAs(User::find(static::USERID))->json('POST', route('api.order.store'), $params);
        $response->assertJsonStructure([
            'data' => [
                'virtual_account'
            ]
        ]);
    }

    public function testDelete()
    {
        
        $order = new order;
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

        $response = $this->actingAs(User::find(static::USERID))->json('delete', route('api.order.delete', $order->id));
        $response->assertStatus(200);
    }

    public function testCallback()
    {
        
        $order = new order;
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

        $response = $this->actingAs(User::find(static::USERID))->post(route('api.order.callback', ['serial' => $order->serial, 'status' => 'success']));
        $response->assertRedirect(route('callback.complete'));
    }
}
