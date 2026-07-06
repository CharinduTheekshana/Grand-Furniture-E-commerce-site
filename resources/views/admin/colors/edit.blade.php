@extends('layouts.admin')
@section('title', 'Edit Color')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Edit Color</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Edit: {{ $color->name }}</h4>
                <a href="{{ route('admin.colors.index') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
            <div class="card-body pt-15">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible mb-20">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.colors.update', $color) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-15">
                        <label class="form-label">Color Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $color->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-20">
                        <label class="form-label">Color Code</label>
                        <div class="d-flex gap-10 align-items-center">
                            <input type="color" id="color-picker"
                                   value="{{ old('color_code', $color->color_code ?? '#000000') }}"
                                   style="width:50px;height:42px;border-radius:6px;
                                          cursor:pointer;border:1px solid #ddd;padding:2px;">
                            <input type="text" name="color_code" id="hex-text"
                                   class="form-control"
                                   value="{{ old('color_code', $color->color_code) }}"
                                   placeholder="#000000" maxlength="7">
                        </div>
                    </div>
                    <div class="d-flex gap-10">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-save-line me-1"></i> Update Color
                        </button>
                        <a href="{{ route('admin.colors.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var picker = document.getElementById('color-picker');
var hexText = document.getElementById('hex-text');
picker.addEventListener('input', function() {
    hexText.value = this.value.toUpperCase();
});
hexText.addEventListener('input', function() {
    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
        picker.value = this.value;
    }
});
</script>
@endpush