<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show($id)
    {
        $order = Order::with(['items.productOffer.product', 'items.productOffer.vendor'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.show', ['order' => $order]);
    }
}
