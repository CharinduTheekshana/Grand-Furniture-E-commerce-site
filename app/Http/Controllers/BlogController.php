<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('is_published', true)->latest();

        if ($request->q) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $blogs = $query->paginate(6);

        return view('pages.blog-index', compact('blogs'));
    }

    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();

        return view('pages.blog-show', compact('blog'));
    }
}
