<?php

namespace App\Repository;

use App\Model\Product;
use Carbon\Carbon;

class ProductRepository
{
    public function computeProduct($inputOrder)
    {
        \DB::beginTransaction();

        foreach ($inputOrder as $serial => $item) {
            $product = Product::where('serial', $serial)->first();
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
}
