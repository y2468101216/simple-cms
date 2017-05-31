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
        $params = $request->all();
        $user = Auth::user();
        $this->repo->compelete($user, $params);

        return response('', 200);
    }
}
