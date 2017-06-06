<?php

namespace App\Repository;

use App\Model\Product;
use Carbon\Carbon;

class ProductRepository
{
    public function subProducts($products) : bool
    {
        \DB::beginTransaction();

        foreach ($products as $id => $item) {
            $product = Product::find($id);
            $qty = $item['quantity'];
            
            if ($qty > $product->quantity) {
                \DB::rollback();
                return false;
            }

            $product->quantity -= $qty;
            $product->save();
        }

        \DB::commit();

        return true;
    }

    public function addProducts($products) : bool
    {
        \DB::beginTransaction();

        foreach ($products as $id => $item) {
            $product = Product::find($id);
            $qty = $item['quantity'];

            $product->quantity += $qty;
            $product->save();
        }

        \DB::commit();

        return true;
    }
}
