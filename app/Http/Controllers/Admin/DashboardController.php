<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount    = Product::count();
        $blogCount       = Blog::count();
        $contactCount    = Contact::count();
        $userCount       = User::count();
        $recentProducts  = Product::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'productCount', 'blogCount', 'contactCount',
            'userCount', 'recentProducts'
        ));
    }
}
