<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    public function create()
    {
        return view('customer.custom-order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'occasion' => 'required|string|max:255',
            'description' => 'required|string',
            'preferred_colors' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'reference_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'required|string|max:20',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'Pending';

        // Handle image upload
        if ($request->hasFile('reference_image')) {
            $imagePath = $request->file('reference_image')->store('custom_orders', 'public');
            $data['reference_image'] = $imagePath;
        }

        CustomOrder::create($data);

        // Log the activity
        ActivityLog::record('Custom Order Request', 'User submitted custom order request for ' . $request->occasion);

        return redirect()->route('customer.dashboard')->with('success', 'Custom order request submitted successfully! We will contact you within 24 hours.');
    }
}