<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Addon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $addons = Addon::all();
        return view('admin.products.create', compact('categories', 'colors', 'addons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'color_id' => 'nullable|exists:colors,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['image', 'addon_ids']);

        // HANDLE IMAGE UPLOAD (Bulletproof Extension Handling)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Force lowercase extension para iwas .JPG vs .jpg conflict
            $extension = strtolower($image->getClientOriginalExtension());
            
            // Fallback kung sakaling walang extension ang file
            if (empty($extension)) {
                $extension = 'jpg';
            }
            
            // Generate unique filename WITH extension
            $imageName = time() . '_' . uniqid() . '.' . $extension;
            
            // Move to public/images folder
            $image->move(public_path('images'), $imageName);
            
            // Save to database
            $data['image'] = $imageName;
        } else {
            // Default image kung walang upload
            $data['image'] = 'default-product.jpg';
        }

        $product = Product::create($data);

        // Sync addons if needed
        if ($request->has('addon_ids')) {
            $product->addons()->sync($request->addon_ids);
        }

        ActivityLog::record('Add Product', 'Admin ' . Auth::user()->name . ' added new product: ' . $product->name);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $colors = Color::all();
        $addons = Addon::all();
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'addons'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'color_id' => 'nullable|exists:colors,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['image', 'addon_ids']);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && File::exists(public_path('images/' . $product->image))) {
                File::delete(public_path('images/' . $product->image));
            }

            // Upload new image with strict extension handling
            $image = $request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());
            if (empty($extension)) {
                $extension = 'jpg';
            }
            
            $imageName = time() . '_' . uniqid() . '.' . $extension;
            $image->move(public_path('images'), $imageName);
            
            $data['image'] = $imageName;
        }

        $product->update($data);

        // Sync addons if needed
        if ($request->has('addon_ids')) {
            $product->addons()->sync($request->addon_ids);
        }

        ActivityLog::record('Update Product', 'Admin ' . Auth::user()->name . ' updated product: ' . $product->name);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image if exists
        if ($product->image && File::exists(public_path('images/' . $product->image))) {
            File::delete(public_path('images/' . $product->image));
        }

        ActivityLog::record('Delete Product', 'Admin ' . Auth::user()->name . ' deleted product: ' . $product->name);

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}