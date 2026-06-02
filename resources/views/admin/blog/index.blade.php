@extends('layouts.admin')
@section('title', 'Blog Posts')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Blog Posts</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Blog</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4><i class="ri-article-line me-1"></i> All Posts</h4>
                <a class="btn btn-primary" href="{{ route('admin.blog.create') }}">
                    <i class="ri-add-line me-1"></i> New Post
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>
                                    @if($blog->image)
                                        <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
                                             style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <div class="avatar avatar-sm bg-secondary-transparent text-secondary"
                                             style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                                            <i class="ri-image-line"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $blog->title }}</div>
                                    <small class="text-muted">/blog/{{ $blog->slug }}</small>
                                </td>
                                <td>
                                    @if($blog->is_published)
                                        <span class="badge bg-success-transparent text-success">Published</span>
                                    @else
                                        <span class="badge bg-warning-transparent text-warning">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $blog->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-8">
                                        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                                           class="btn-icon btn-success-light" title="View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.blog.edit', $blog) }}"
                                           class="btn-icon btn-info-light" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $blog) }}" method="POST"
                                              onsubmit="return confirm('Delete this post?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger-light" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-article-line fs-32 d-block mb-10"></i>
                                    No blog posts yet.
                                    <a href="{{ route('admin.admin-blog.create') }}">Create first post</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($blogs, 'links'))
                <div class="p-3">{{ $blogs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
