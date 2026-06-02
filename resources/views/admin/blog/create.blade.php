@extends('layouts.admin')
@section('title', 'New Blog Post')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">New Blog Post</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible mb-20">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-20">
    <div class="col-xxl-8 col-xl-8">
        <div class="card mb-20">
            <div class="card-header justify-between">
                <h4>Post Content</h4>
                <a href="{{ route('admin.blog.index') }}">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
            <div class="card-body pt-15">
                <div class="row gy-15">
                    <div class="col-xl-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Blog post title">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-xl-12">
                        <label class="form-label">Excerpt <small class="text-muted">(short summary)</small></label>
                        <textarea name="excerpt" rows="2"
                                  class="form-control @error('excerpt') is-invalid @enderror"
                                  placeholder="Short description shown in listings...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-xl-12">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="blog-content" rows="14"
                                  class="form-control @error('content') is-invalid @enderror"
                                  placeholder="Write your blog post content here...">{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-4 col-xl-4">
        <div class="card mb-20">
            <div class="card-header"><h4>Featured Image</h4></div>
            <div class="card-body pt-15">
                <div id="img-preview-wrap" class="mb-15" style="display:none;">
                    <img id="img-preview" src="" style="width:100%;border-radius:8px;max-height:200px;object-fit:cover;">
                </div>
                <input type="file" name="image" id="blog-image" accept="image/*"
                       class="form-control @error('image') is-invalid @enderror">
                <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="card mb-20">
            <div class="card-header"><h4>Publish</h4></div>
            <div class="card-body pt-15">
                <div class="form-check form-switch mb-15">
                    <input class="form-check-input" type="checkbox" name="is_published"
                           id="is_published" value="1" checked>
                    <label class="form-check-label" for="is_published">
                        Publish immediately
                        <small class="d-block text-muted">Visible to all visitors</small>
                    </label>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-save-line me-1"></i> Publish Post
                </button>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-light w-100 mt-10">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
document.getElementById('blog-image').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(r) {
            document.getElementById('img-preview').src = r.target.result;
            document.getElementById('img-preview-wrap').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
