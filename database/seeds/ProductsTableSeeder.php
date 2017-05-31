<?php

use Illuminate\Database\Seeder;
use App\Model\Product;
use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product;
        $product->serial = 'abc123';
        $product->status = Product::ENABLE;
        $product->quantity = 10;
        $product->amount = 1000;
        $product->save();

        $product = new Product;
        $product->serial = 'abc124';
        $product->status = Product::ENABLE;
        $product->quantity = 20;
        $product->amount = 500;
        $product->save();

        $product = new Product;
        $product->serial = 'abc125';
        $product->status = Product::ENABLE;
        $product->quantity = 30;
        $product->amount = 200;
        $product->save();
    }
}
