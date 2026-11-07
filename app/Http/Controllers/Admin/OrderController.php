<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        // 1. Kunin ang Regular Orders
        $regularOrders = Order::with('user')->get()->map(function($order) {
            $order->order_type = 'Regular';
            return $order;
        });

        // 2. Kunin ang LAHAT ng Custom Orders (INAYOS: Inalis ang 'Approved' filter para makita ang Pending)
        $customOrders = CustomOrder::with('user')->get()->map(function($order) {
            $order->order_type = 'Custom';
            return $order;
        });

        // 3. Pag-samahin at i-sort by latest created_at
        $orders = $regularOrders->merge($customOrders)->sortByDesc('created_at')->values();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id, $type = 'regular')
    {
        if ($type === 'custom') {
            $order = CustomOrder::with('user')->findOrFail($id);
            return view('admin.custom-orders.show', compact('order'));
        }
        
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id, $type = 'regular')
    {
        if ($type === 'custom') {
            $order = CustomOrder::findOrFail($id);
            $order->status = $request->status;
            $order->admin_notes = $request->admin_notes ?? null;
            $order->save();
            return redirect()->back()->with('success', 'Custom order status updated successfully!');
        }

        // Regular Order Logic
        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        if ($request->status === 'Completed' && $oldStatus !== 'Completed') {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Exception $e) {
                Log::error('Failed to send status update email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully! Customer has been notified via email.');
    }

    public function destroy($id, $type)
    {
        if ($type === 'custom') {
            $order = CustomOrder::findOrFail($id);
            if ($order->reference_image && File::exists(public_path('storage/' . $order->reference_image))) {
                File::delete(public_path('storage/' . $order->reference_image));
            }
            $order->delete();
        } else {
            $order = Order::findOrFail($id);
            $order->delete();
        }

        return redirect()->back()->with('success', 'Order deleted successfully!');
    }
}