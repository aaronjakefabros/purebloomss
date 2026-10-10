<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Addon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('stock', '>', 0);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->get();
        $categories = Category::where('is_active', true)->get();
        $colors = Color::all();

        return view('home', compact('products', 'categories', 'colors'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $colors = Color::all();
        $addons = Addon::where('is_active', true)->get();

        return view('product.show', compact('product', 'colors', 'addons'));
    }
}