<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\OrderRepository;
use App\Model\Order;
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

    public function delete(Request $request, $id)
    {
        $order = Order::find($id);
        $this->repo->cancel($order);
        
        return response()->json('');
    }

    public function callback(Request $request)
    {
        $serial = $request->input('serial', '');
        $status = $request->input('status', '');

        if ($status == 'success') {
            return redirect()->route('callback.complete');
        }

        abort(404);
    }
}
