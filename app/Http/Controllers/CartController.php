<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Color;
use App\Models\Addon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderReceiptMail;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Get Selected Add-ons and Calculate Addon Price
        $addonIds = $request->input('addons', []);
        $addonTotal = 0;
        $addonNames = [];

        if (!empty($addonIds)) {
            $addons = Addon::whereIn('id', $addonIds)->get();
            foreach ($addons as $addon) {
                $addonTotal += $addon->price;
                $addonNames[] = $addon->name;
            }
        }

        // 2. Get Selected Color Name
        $colorName = 'Default';
        if ($request->has('color_id')) {
            $color = Color::find($request->color_id);
            if ($color) $colorName = $color->name;
        }

        // 3. Calculate Final Price per Item
        $finalPrice = $product->price + $addonTotal;

        // 4. Create unique cart key based on product + color + addons
        $cartKey = $id . '_' . $request->color_id . '_' . implode('_', $addonIds);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $id,
                'name' => $product->name,
                'price' => $finalPrice,
                'base_price' => $product->price,
                'quantity' => $request->quantity ?? 1,
                'image' => $product->image,
                'color' => $colorName,
                'addons' => implode(', ', $addonNames),
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart with selected options!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Removed from cart!');
    }

    public function update(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        $rules = [
            'email' => 'required|email',
            'delivery_method' => 'required|in:Delivery,Pickup',
            'use_tokens' => 'nullable|boolean',
        ];

        if ($request->delivery_method === 'Delivery') {
            $rules['phone_number'] = 'required|string|max:20';
            $rules['delivery_address'] = 'required|string';
            $rules['delivery_date'] = 'required|date|after_or_equal:today';
            $rules['delivery_time'] = 'required|string';
        }

        $request->validate($rules);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryMethod = $request->delivery_method;
        $deliveryFee = ($deliveryMethod === 'Delivery') ? 100.00 : 0.00;
        $totalAmount = $subtotal + $deliveryFee;

        // ==========================================
        // TOKEN REDEMPTION LOGIC (May Minimum ₱1,000 Rule)
        // ==========================================
        $user = Auth::user();
        $tokenDiscount = 0;
        $tokensUsed = false;

        if ($request->boolean('use_tokens') && $user && $user->wallet_balance >= 200 && $totalAmount >= 1000) {
            $tokenDiscount = 200.00;
            $user->decrement('wallet_balance', 200);
            $tokensUsed = true;
        }
        
        $finalTotal = $totalAmount - $tokenDiscount;
        // ==========================================

        // 1. Create the main Order record
        $order = Order::create([
            'user_id' => $user->id, 
            'total_amount' => $finalTotal,
            'status' => 'Pending',
            'customer_email' => $request->email,
            'delivery_method' => $deliveryMethod,
            'delivery_fee' => $deliveryFee,
            'phone_number' => $request->phone_number,
            'delivery_address' => $request->delivery_address,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,
            'messenger_link' => 'https://m.me/pureblooms?ref=order_' . now()->timestamp,
        ]);

        // 2. Create Order Items AND DEDUCT STOCK (IN-UPDATE PARA MAS SOLID)
        foreach ($cart as $cartKey => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'color' => $item['color'] ?? null,
                'addons' => $item['addons'] ?? null,
            ]);

            // MAS SOLID NA STOCK DEDUCTION
            $product = Product::find($item['product_id']);
            if ($product) {
                // Gamitin ang decrement() para siguradong mabawasan ang stock nang atomic at iwas negative
                $product->decrement('stock', $item['quantity']);
            }
        }

        // ==========================================
        // TOKEN REWARD LOGIC (Earn 50 Tokens)
        // ==========================================
        $rewardTokens = 50; 
        $user->increment('wallet_balance', $rewardTokens);
        // ==========================================

        // 3. I-record ang pag-order sa Activity Log
        $logDescription = 'Order #' . $order->id . ' placed successfully. Total: ₱' . number_format($finalTotal, 2);
        if ($tokensUsed) {
            $logDescription .= ' (Used 200 Tokens for discount)';
        }
        $logDescription .= " | Earned {$rewardTokens} Tokens reward.";
        ActivityLog::record('Place Order', $logDescription);

        // 4. Send Email Receipt
        try {
            Mail::to($order->customer_email)->send(new OrderReceiptMail($order));
        } catch (\Exception $e) {
            Log::error('Failed to send order receipt email: ' . $e->getMessage());
        }

        // 5. Clear Cart and Redirect
        session()->forget('cart');
        
        $successMessage = 'Order placed successfully! ';
        if ($tokensUsed) {
            $successMessage .= '200 Tokens used for discount. ';
        }
        $successMessage .= "You earned {$rewardTokens} Tokens as a reward! Check your email for the receipt.";
        
        return redirect()->route('home')->with('success', $successMessage);
    }
}