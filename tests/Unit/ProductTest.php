<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repository\ProductRepository;
use App\Model\Product;

class ProductTest extends TestCase
{
    use DatabaseTransactions;
    const QTY = 1;
    const PRODUCTID = 1;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testsubProduct()
    {
        $productTest = Product::find(static::PRODUCTID);

        $productTestQty = $productTest->quantity;
        $expect = $productTestQty - static::QTY;

        $products = [
            $productTest->id => ['quantity' => static::QTY]
        ];

        $productRepository = new ProductRepository;

        $productRepository->subProducts($products);

        $productTest = $productTest->fresh();
        $actual = $productTest->quantity;

        $this->assertEquals($actual, $expect);
    }

    public function testAddProduct()
    {
        $productTest = Product::find(static::PRODUCTID);

        $productTestQty = $productTest->quantity;
        $expect = $productTestQty + static::QTY;

        $products = [
            $productTest->id => ['quantity' => static::QTY]
        ];

        $productRepository = new ProductRepository;
        $productRepository->addProducts($products);

        $productTest = $productTest->fresh();
        $actual = $productTest->quantity;

        $this->assertEquals($actual, $expect);
    }
}
