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

        $blogs       = $query->paginate(6);
        $recentBlogs = Blog::where('is_published', true)->latest()->take(5)->get();
        $archives    = Blog::where('is_published', true)
                        ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                        ->groupBy('year', 'month')
                        ->orderByDesc('year')->orderByDesc('month')->get();

        return view('pages.blog-index', compact('blogs', 'recentBlogs', 'archives'));
    }

    public function show($slug)
    {
        $blog         = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $relatedBlogs = Blog::where('is_published', true)->where('id', '!=', $blog->id)->latest()->take(3)->get();
        $recentBlogs  = Blog::where('is_published', true)->latest()->take(5)->get();
        $archives     = Blog::where('is_published', true)
                            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                            ->groupBy('year', 'month')
                            ->orderByDesc('year')->orderByDesc('month')->get();

        return view('pages.blog-show', compact('blog', 'relatedBlogs', 'recentBlogs', 'archives'));
    }
}
