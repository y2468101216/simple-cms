<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\OrderRepository;
use Auth;

class OrderController extends Controller
{
    public $repo;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->repo = $orderRepository;
    }

    public function store(Request $request)
    {
        $products = $request->input('products');
        $method = $request->input('method');
        $user = Auth::user();
        $order = $this->repo->create($user, $products, $method);

        return response()->json([
            'data' => [
                'virtual_account' => $order->virtual_account
            ]
        ]);
    }
}
