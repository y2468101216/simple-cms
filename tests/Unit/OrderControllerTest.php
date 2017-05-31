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
    public function testStore()
    {
        
        $productTest = Product::find(static::PRODUCTID);

        $inputOrder = [
            $productTest->serial => ['quantity' => static::QTY]
        ];

        $response = $this->actingAs(User::find(static::USERID))->json('POST', route('api.order.store'), $inputOrder);
        $response->assertStatus(200);
    }
}
