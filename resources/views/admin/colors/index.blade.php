@extends('layouts.admin')
@section('title', 'Colors')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15 mb-20">
            <h1 class="page-title fs-18 lh-1">Color Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Colors</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xxl-8">
        <div class="card">
            <div class="card-header"><h4><i class="ri-palette-line me-1"></i> All Colors</h4></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Name</th>
                            <th>Color Code</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                        <tr>
                            <td>
                                @if($color->color_code)
                                <div style="width:32px;height:32px;border-radius:50%;
                                            background:{{ $color->color_code }};
                                            border:2px solid #ddd;"></div>
                                @else
                                <div style="width:32px;height:32px;border-radius:50%;
                                            background:#f0f0f0;border:2px solid #ddd;
                                            display:flex;align-items:center;justify-content:center;">
                                    <i class="ri-question-line fs-14"></i>
                                </div>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $color->name }}</td>
                            <td>
                                @if($color->color_code)
                                    <code>{{ $color->color_code }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-transparent text-primary">
                                    {{ $color->products_count }} products
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-8">
                                    <a href="{{ route('admin.colors.edit', $color) }}"
                                       class="btn-icon btn-info-light" title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('admin.colors.destroy', $color) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete {{ $color->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-danger-light">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="ri-palette-line fs-32 d-block mb-10"></i>
                                No colors yet. Add your first color!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xxl-4">
        <div class="card mb-20">
            <div class="card-header"><h4>Add New Color</h4></div>
            <div class="card-body pt-15">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible mb-15">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    @foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach
                </div>
                @endif

                <form action="{{ route('admin.colors.store') }}" method="POST">
                    @csrf
                    <div class="mb-15">
                        <label class="form-label">Color Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Black, Walnut Brown">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-20">
                        <label class="form-label">Color Code <span class="text-muted fs-12">(optional)</span></label>
                        <div class="d-flex gap-10 align-items-center">
                            <input type="color" id="color-picker"
                                   value="{{ old('color_code', '#000000') }}"
                                   style="width:50px;height:42px;border-radius:6px;
                                          cursor:pointer;border:1px solid #ddd;padding:2px;">
                            <input type="text" name="color_code" id="hex-text"
                                   class="form-control"
                                   value="{{ old('color_code') }}"
                                   placeholder="#000000" maxlength="7">
                        </div>
                        <small class="text-muted">Leave blank if no specific color code</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-add-line me-1"></i> Add Color
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h4><i class="ri-stack-line me-1"></i> Bulk Add Colors</h4></div>
            <div class="card-body pt-15">
                <form action="{{ route('admin.colors.bulk') }}" method="POST">
                    @csrf
                    <div class="mb-15">
                        <label class="form-label">One color per line</label>
                        <textarea name="bulk_colors" rows="6" class="form-control"
                                  placeholder="Black&#10;White, #ffffff&#10;Walnut Brown, #5c4033"></textarea>
                        <small class="text-muted">
                            Format: <code>Name</code> or <code>Name, #hexcode</code>. Existing names are skipped.
                        </small>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="ri-add-line me-1"></i> Add All Colors
                    </button>
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