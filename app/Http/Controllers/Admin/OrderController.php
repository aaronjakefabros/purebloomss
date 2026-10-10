<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- IDINAGDAG
use App\Mail\OrderStatusUpdatedMail; // <-- IDINAGDAG

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // <-- IDINAGDAG: Mag-send ng email KAPAG naging "Completed" ang status
        if ($request->status === 'Completed' && $oldStatus !== 'Completed') {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully! Customer has been notified via email.');
    }
}