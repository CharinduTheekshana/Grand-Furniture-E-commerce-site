<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::withCount('products')->latest()->get();
        return view('admin.colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:50|unique:colors,name',
            'color_code' => 'nullable|string|max:7',
        ]);

        Color::create([
            'name'       => $request->name,
            'color_code' => $request->color_code ?: null,
        ]);

        return back()->with('success', 'Color "' . $request->name . '" added!');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name'       => 'required|string|max:50|unique:colors,name,' . $color->id,
            'color_code' => 'nullable|string|max:7',
        ]);

        $color->update([
            'name'       => $request->name,
            'color_code' => $request->color_code ?: null,
        ]);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Color updated!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return back()->with('success', 'Color deleted.');
    }
}