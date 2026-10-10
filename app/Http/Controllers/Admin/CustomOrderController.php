<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    public function index()
    {
        $customOrders = CustomOrder::with('user')->latest()->get();
        return view('admin.custom-orders.index', compact('customOrders'));
    }

    public function show($id)
    {
        $order = CustomOrder::with('user')->findOrFail($id);
        return view('admin.custom-orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Completed',
            'admin_notes' => 'nullable|string',
        ]);

        $order = CustomOrder::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->admin_notes = $request->admin_notes;
        $order->save();

        $adminName = Auth::user()->name;
        ActivityLog::record('Update Custom Order Status', "Admin {$adminName} changed Custom Order #{$id} status from '{$oldStatus}' to '{$request->status}'.");

        return redirect()->back()->with('success', 'Custom order status updated successfully!');
    }
}