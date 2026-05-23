@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Create Blog Post</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-area ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="login-title">
                    <h3>New Blog Post</h3>
                    <span>Write and publish a new blog post for your store.</span>
                </div>
                <div class="login-form">
                    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group login-page">
                            <label>Blog Title <span>*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" placeholder="e.g., Furniture Care Tips" required>
                            @error('title')<span class="text-danger" style="font-size:12px;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group login-page">
                            <label>Content <span>*</span></label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                      rows="8" placeholder="Write your blog post content here...">{{ old('content') }}</textarea>
                            @error('content')<span class="text-danger" style="font-size:12px;">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group login-page">
                            <label>Excerpt <span style="color:#999;font-size:12px;">(Short summary — optional)</span></label>
                            <input type="text" name="excerpt" class="form-control"
                                   value="{{ old('excerpt') }}" placeholder="Brief summary...">
                        </div>

                        <div class="form-group login-page">
                            <label>Featured Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                        </div>

                        <div style="display:flex;gap:10px;margin-top:20px;">
                            <button type="submit" class="btn btn-default login-btn">
                                <i class="fa fa-paper-plane"></i> Publish Post
                            </button>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-default login-btn"
                               style="background:#999;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="login-title"><h3>Tips</h3></div>
                <ul style="color:#666;font-size:13px;line-height:2;">
                    <li>Use a descriptive title for SEO</li>
                    <li>Add a featured image for better appearance</li>
                    <li>Blog post appears on homepage after publishing</li>
                    <li>Write excerpt for blog list page preview</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
