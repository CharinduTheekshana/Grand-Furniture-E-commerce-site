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
    public function storeBulk(Request $request)
    {
        $request->validate([
            'bulk_colors' => 'required|string',
        ]);

        $lines   = preg_split('/\r\n|\r|\n/', $request->bulk_colors);
        $added   = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $parts     = array_map('trim', explode(',', $line));
            $name      = $parts[0] ?? null;
            $colorCode = $parts[1] ?? null;

            if (!$name) continue;

            if (Color::where('name', $name)->exists()) {
                $skipped++;
                continue;
            }

            Color::create([
                'name'       => $name,
                'color_code' => $colorCode ?: null,
            ]);
            $added++;
        }

        $message = "$added color(s) added.";
        if ($skipped > 0) $message .= " $skipped skipped (already exist).";

        return back()->with('success', $message);
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