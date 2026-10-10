<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Kunin ang statistics ng user
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'Pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'Completed')->count();
        
        // Kunin ang last 5 orders para sa dashboard
        $recentOrders = Order::where('user_id', $user->id)
                             ->latest()
                             ->take(5)
                             ->get();

        return view('customer.dashboard', compact('user', 'totalOrders', 'pendingOrders', 'completedOrders', 'recentOrders'));
    }
}