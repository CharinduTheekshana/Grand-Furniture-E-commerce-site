<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(12);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $file = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/blogs'), $file);
            $validated['image'] = $file;
        }

        $validated['slug']         = $this->uniqueSlug($validated['title']);
        $validated['is_published'] = true;

        Blog::create($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post published!');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $file = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/blogs'), $file);
            $validated['image'] = $file;
        }

        $validated['slug'] = $this->uniqueSlug($validated['title'], $blog->id);

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted!');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'blog-post';
        $slug = $base;
        $i = 2;

        while (Blog::when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                    ->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
