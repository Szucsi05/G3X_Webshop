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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Rendel\u00e9s nem tal\u00e1lhat\u00f3.');
        }

        return view('orders.show', ['order' => $order]);
    }
}
