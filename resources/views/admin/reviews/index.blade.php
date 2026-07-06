@extends('layouts.admin')
@section('title', 'Customer Reviews')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box d-flex-between flex-wrap gap-15">
            <h1 class="page-title fs-18 lh-1">Customer Reviews</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-example1 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- ── Stat Cards ──────────────────────────────── --}}
<div class="row g-3 mt-1">
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-primary-transparent text-primary">
                    <i class="ri-message-3-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Total Reviews</span>
                    <h2 class="mb-5">{{ $totalReviews }}</h2>
                    <span class="fs-12 text-muted">All time</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-warning-transparent text-warning">
                    <i class="ri-star-smile-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Avg. Rating</span>
                    <h2 class="mb-5">{{ number_format($avgRating, 1) }} <small class="fs-14 text-muted">/5</small></h2>
                    <span class="fs-12 text-muted">Overall</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-success-transparent text-success">
                    <i class="ri-thumb-up-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Positive Reviews</span>
                    <h2 class="mb-5">{{ $positivePercent }}%</h2>
                    <span class="text-success fs-12">Rating ≥ 3 stars</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body mini-card-body d-flex align-center gap-16">
                <div class="avatar avatar-xl bg-info-transparent text-info">
                    <i class="ri-time-line fs-42"></i>
                </div>
                <div class="card-content">
                    <span class="d-block fs-16 mb-5">Recent Reviews</span>
                    <h2 class="mb-5">{{ $recentReviews }}</h2>
                    <span class="fs-12 text-muted">Last 7 days</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Rating Breakdown ────────────────────────── --}}
<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="product-review-rating-wrapper" style="display:flex;align-items:center;gap:32px;padding:24px;">
                <div class="product-rating-box" style="min-width:160px;text-align:center;border-right:1px solid var(--border-color,#e9e9e9);padding-right:32px;">
                    <div style="font-size:56px;font-weight:700;line-height:1;color:var(--color-heading);">
                        {{ number_format($avgRating, 1) }}
                    </div>
                    <div style="color:#f59e0b;font-size:20px;margin:8px 0;">
                        @for($i=1;$i<=5;$i++)
                            <i class="ri-star-{{ $i <= round($avgRating) ? 'fill' : 'line' }}"></i>
                        @endfor
                    </div>
                    <span style="font-size:13px;color:var(--color-body);">({{ $totalReviews }} Reviews)</span>
                </div>
                <div style="flex:1;">
                    @foreach($breakdown as $star => $data)
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <span style="min-width:12px;font-size:14px;font-weight:600;">{{ $star }}</span>
                        <div class="progress" style="flex:1;height:10px;border-radius:6px;">
                            <div class="progress-bar bg-{{ $data['color'] }}" role="progressbar" style="width:{{ $data['percent'] }}%"></div>
                        </div>
                        <span style="min-width:36px;font-size:13px;color:var(--color-body);">{{ $data['percent'] }}%</span>
                        <span style="min-width:30px;font-size:13px;color:var(--color-body);">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Reviews Table ───────────────────────────── --}}
<div class="row mt-20">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header justify-between">
                <h4>Customers Reviews</h4>
            </div>
            <div class="card-body pt-15">
                <div class="table-responsive">
                    <table id="dataTableDefault" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Reviewer</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            @php
                                $avgStar = round(($review->quality + $review->price + $review->value) / 3);
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex-items gap-10">
                                        @if($review->product && $review->product->image)
                                        <div class="avatar avatar-md">
                                            <img src="{{ asset('storage/' . $review->product->image) }}"
                                                 class="radius-6"
                                                 style="width:42px;height:42px;object-fit:cover;">
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-medium">{{ $review->product->name ?? '—' }}</h6>
                                            <small class="text-muted">{{ $review->product->category->name ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex-items gap-10">
                                        <div class="avatar avatar-md radius-100 bg-primary-transparent text-primary fw-7"
                                             style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                                            {{ strtoupper(substr($review->user->name ?? $review->nickname ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->user->name ?? $review->nickname ?? '—' }}</h6>
                                            <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="color:#f59e0b;font-size:14px;margin-bottom:4px;">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="ri-star-{{ $i <= $avgStar ? 'fill' : 'line' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="fw-medium mb-2">{{ $review->summary ?? '' }}</div>
                                    <span class="text-muted" style="white-space:normal;max-width:300px;display:inline-block;">
                                        {{ Str::limit($review->review ?? '', 80) }}
                                    </span>
                                </td>
                                <td style="white-space:nowrap;">
                                    {{ $review->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $review->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                                class="btn-icon btn-success-light view-review-btn"
                                                title="View"
                                                data-product="{{ $review->product->name ?? '—' }}"
                                                data-reviewer="{{ $review->user->name ?? $review->nickname ?? '—' }}"
                                                data-email="{{ $review->user->email ?? '' }}"
                                                data-summary="{{ $review->summary ?? '' }}"
                                                data-review="{{ $review->review ?? '' }}"
                                                data-quality="{{ $review->quality }}"
                                                data-price="{{ $review->price }}"
                                                data-value="{{ $review->value }}"
                                                data-avg="{{ $avgStar }}"
                                                data-date="{{ $review->created_at->format('d M Y, h:i A') }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewModal">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <form action="{{ url('/admin-panel/reviews/' . $review->id) }}"
                                              method="POST" onsubmit="return confirm('Delete this review?')">
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
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="ri-star-line fs-32 d-block mb-10"></i>
                                    No reviews yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($reviews, 'links'))
                <div class="p-3">{{ $reviews->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Review Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-star-line me-2"></i> Review Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted fs-12">Product</label>
                        <p class="fw-medium mb-0" id="r-product"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-12">Date</label>
                        <p class="fw-medium mb-0" id="r-date"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-12">Reviewer</label>
                        <p class="fw-medium mb-0" id="r-reviewer"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-12">Email</label>
                        <p class="fw-medium mb-0" id="r-email"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted fs-12">Overall Rating</label>
                        <div id="r-stars" style="color:#f59e0b;font-size:20px;"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted fs-12">Quality</label>
                        <div id="r-quality" style="color:#f59e0b;"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted fs-12">Price</label>
                        <div id="r-price" style="color:#f59e0b;"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted fs-12">Value</label>
                        <div id="r-value" style="color:#f59e0b;"></div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted fs-12">Summary</label>
                        <p class="fw-medium mb-0" id="r-summary"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted fs-12">Full Review</label>
                        <p id="r-review" style="white-space:pre-wrap;background:#f9f9f9;
                           padding:12px;border-radius:6px;margin:0;"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dataTableDefault').DataTable({
            pageLength: 10,
            order: [[3, 'desc']],
            columnDefs: [{ orderable: false, targets: [4] }]
        });
    }
});

// Review modal
document.querySelectorAll('.view-review-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        function stars(n) {
            var s = '';
            for (var i = 1; i <= 5; i++) {
                s += '<i class="ri-star-' + (i <= n ? 'fill' : 'line') + '"></i>';
            }
            return s;
        }
        document.getElementById('r-product').textContent  = this.dataset.product;
        document.getElementById('r-reviewer').textContent = this.dataset.reviewer;
        document.getElementById('r-email').textContent    = this.dataset.email;
        document.getElementById('r-summary').textContent  = this.dataset.summary;
        document.getElementById('r-review').textContent   = this.dataset.review;
        document.getElementById('r-date').textContent     = this.dataset.date;
        document.getElementById('r-stars').innerHTML      = stars(parseInt(this.dataset.avg));
        document.getElementById('r-quality').innerHTML    = stars(parseInt(this.dataset.quality));
        document.getElementById('r-price').innerHTML      = stars(parseInt(this.dataset.price));
        document.getElementById('r-value').innerHTML      = stars(parseInt(this.dataset.value));
    });
});
</script>
@endpush