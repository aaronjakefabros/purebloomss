<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::latest()->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        // TINANGGAL ANG IMAGE VALIDATION
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // DIRECT SAVE WITHOUT IMAGE
        Addon::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => true, // Kung meron kang column na ito sa database
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully!');
    }

    public function edit($id)
    {
        $addon = Addon::findOrFail($id);
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, $id)
    {
        // TINANGGAL ANG IMAGE VALIDATION
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $addon = Addon::findOrFail($id);
        
        // DIRECT UPDATE WITHOUT IMAGE
        $addon->name = $request->name;
        $addon->description = $request->description;
        $addon->price = $request->price;
        $addon->save();

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully!');
    }

    public function destroy($id)
    {
        Addon::findOrFail($id)->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted successfully!');
    }
}