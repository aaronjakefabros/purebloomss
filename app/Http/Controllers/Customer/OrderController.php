<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Listahan ng lahat ng orders ng user
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    // Tingnan ang specific order details
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
                     ->with('items.product') // Load items and product details
                     ->findOrFail($id);
        
        return view('customer.orders.show', compact('order'));
    }
}