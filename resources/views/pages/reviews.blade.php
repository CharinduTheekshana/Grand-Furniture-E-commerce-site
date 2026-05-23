@extends('layouts.app')
@section('title', 'My Reviews - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>My Reviews</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="cart-main-area ptb-80">
    <div class="container">

        @if($reviews->count() > 0)
        <div class="grand-card">
            <h4 class="grand-card-title">
                <i class="fa fa-star" style="color:#f6931f;"></i> My Reviews
            </h4>
            <div class="table-content table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Summary</th>
                            <th>Quality</th>
                            <th>Price</th>
                            <th>Value</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if($review->product)
                                <a href="{{ route('product.show', $review->product->slug) }}">
                                    {{ $review->product->name }}
                                </a>
                                @else
                                <span class="text-muted">Product unavailable</span>
                                @endif
                            </td>
                            <td>{{ $review->summary }}</td>
                            <td>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->quality?'-o':'' }}" style="color:#f6931f;"></i>@endfor</td>
                            <td>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->price?'-o':'' }}" style="color:#f6931f;"></i>@endfor</td>
                            <td>@for($s=1;$s<=5;$s++)<i class="fa fa-star{{ $s>$review->value?'-o':'' }}" style="color:#f6931f;"></i>@endfor</td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="grand-empty-state">
            <i class="fa fa-star grand-empty-icon"></i>
            <h3 class="grand-empty-title">No Reviews Yet!</h3>
            <p class="text-muted mb-4">You haven't reviewed any products yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-default login-btn">Start Shopping</a>
        </div>
        @endif

    </div>
</div>

<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30"><div class="contuct-info text-center"><h4>Sign up for news &amp; offers!</h4><p>You may safely unsubscribe at any time</p></div></div>
            <div class="col-xl-6 col-lg-7 offset-lg-1"><div class="search-box"><form action="#"><input type="email" placeholder="Enter your email address"/><button><span class="lnr lnr-envelope"></span></button></form></div></div>
        </div>
    </div>
</div>

@endsection