@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Blog Posts</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="new-product-area pt-80 pb-50">
    <div class="container">

        <div class="row mb-30">
            <div class="col-md-6">
                <div class="section-title">
                    <h2>Manage Blog</h2>
                </div>
            </div>
            <div class="col-md-6 text-end" style="padding-top:20px;">
                <a href="{{ route('admin.blog.create') }}" class="btn btn-default login-btn">
                    <i class="fa fa-plus"></i> New Blog Post
                </a>
            </div>
        </div>

        @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:4px;margin-bottom:20px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="row">
            @forelse($blogs as $blog)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-blog mt-30">
                    <div class="blog-img" style="position:relative;">
                        <a href="{{ route('admin.blog.edit', $blog->id) }}">
                            <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('assets/images/blog/1.jpg') }}"
                                 alt="{{ $blog->title }}"
                                 onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'" />
                        </a>
                        {{-- Action buttons on hover --}}
                        <div style="position:absolute;top:10px;right:10px;display:flex;gap:5px;">
                            <a href="{{ route('admin.blog.edit', $blog->id) }}"
                               style="background:#f6931f;color:#fff;padding:4px 10px;font-size:12px;border-radius:3px;text-decoration:none;">
                                Edit
                            </a>
                            <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST"
                                  style="display:inline;" onsubmit="return confirm('Delete this blog post?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="background:#e74c3c;color:#fff;padding:4px 10px;font-size:12px;border-radius:3px;border:none;cursor:pointer;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="blog-info">
                        <a href="{{ route('admin.blog.edit', $blog->id) }}">
                            <h2>{{ Str::limit($blog->title, 40) }}</h2>
                        </a>
                        <p>{{ Str::limit(strip_tags($blog->content), 80) }}</p>
                        <h4>{{ $blog->created_at->format('d M, Y') }}</h4>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>No blog posts yet.</h4>
                <a href="{{ route('admin.blog.create') }}" class="btn btn-default login-btn mt-3">Create First Post</a>
            </div>
            @endforelse
        </div>

        <div class="mt-4 text-center">
            {{ $blogs->links() }}
        </div>

    </div>
</div>

@endsection
