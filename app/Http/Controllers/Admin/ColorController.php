<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->get();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'hex_code' => 'nullable|string|max:7',
        ]);

        Color::create($request->all());
        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully!');
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $id,
            'hex_code' => 'nullable|string|max:7',
        ]);

        Color::findOrFail($id)->update($request->all());
        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully!');
    }

    public function destroy($id)
    {
        Color::findOrFail($id)->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully!');
    }
}