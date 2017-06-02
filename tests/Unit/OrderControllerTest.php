<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Model\User;
use App\Model\Product;

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
            $productTest->serial => ['quantity' => static::QTY]
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
}
