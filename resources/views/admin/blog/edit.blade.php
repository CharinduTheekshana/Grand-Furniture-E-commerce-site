@extends('layouts.admin')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Edit Blog Post</h3>
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
                    <h3>Edit: {{ Str::limit($blog->title, 40) }}</h3>
                </div>
                <div class="login-form">
                    <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="form-group login-page">
                            <label>Blog Title <span>*</span></label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $blog->title) }}" required>
                        </div>

                        <div class="form-group login-page">
                            <label>Content</label>
                            <textarea name="content" class="form-control" rows="8">{{ old('content', $blog->content) }}</textarea>
                        </div>

                        <div class="form-group login-page">
                            <label>Excerpt</label>
                            <input type="text" name="excerpt" class="form-control"
                                   value="{{ old('excerpt', $blog->excerpt ?? '') }}">
                        </div>

                        <div class="form-group login-page">
                            <label>Replace Image <span style="color:#999;font-size:12px;">(Leave empty to keep current)</span></label>
                            @if($blog->image)
                            <div style="margin-bottom:8px;">
                                <img src="{{ asset('storage/' . $blog->image) }}"
                                     alt="Current" style="height:80px;border:1px solid #eee;padding:3px;"
                                     onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'">
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div style="display:flex;gap:10px;margin-top:20px;">
                            <button type="submit" class="btn btn-default login-btn">
                                <i class="fa fa-save"></i> Update Post
                            </button>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-default login-btn"
                               style="background:#999;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                @if($blog->image)
                <div class="login-title"><h3>Current Image</h3></div>
                <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid"
                     style="border:1px solid #eee;padding:5px;"
                     onerror="this.src='{{ asset('assets/images/blog/1.jpg') }}'">
                @endif

                <div style="margin-top:20px;">
                    <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST"
                          onsubmit="return confirm('Delete this blog post?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-default login-btn w-100"
                                style="background:#e74c3c;">
                            <i class="fa fa-trash"></i> Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
