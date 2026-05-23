@extends('layouts.app')
@section('title', 'Wishlist - Grand Furniture')
@section('content')

<div class="page-title-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title"><h3>Wishlist</h3></div>
            </div>
        </div>
    </div>
</div>

<div class="wishlist-area pt-80 pb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wishlist-content">
                    @if($wishlist->count() > 0)
                    <div class="wishlist-title">
                        <h2>My wishlist</h2>
                    </div>
                    <div class="wishlist-table table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product-remove"><span class="nobr">Remove</span></th>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name"><span class="nobr">Product Name</span></th>
                                    <th class="product-price"><span class="nobr">Unit Price</span></th>
                                    <th class="product-stock-stauts"><span class="nobr">Stock Status</span></th>
                                    <th class="product-add-to-cart"><span class="nobr">Add to Cart</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wishlist as $item)
                                <tr>
                                    <td class="product-remove">
                                        <form action="{{ route('wishlist.toggle', $item->product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background:none;border:none;cursor:pointer;">x</button>
                                        </form>
                                    </td>
                                    <td class="product-thumbnail">
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" />
                                        </a>
                                    </td>
                                    <td class="product-name">
                                        <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </td>
                                    <td class="product-price">
                                        <span class="amount">LKR {{ number_format($item->product->sale_price ?? $item->product->price, 2) }}</span>
                                    </td>
                                    <td class="product-stock-status">
                                        @if($item->product->stock > 0)
                                            <span class="wishlist-in-stock">In Stock</span>
                                        @else
                                            <span class="wishlist-out-stock">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td class="product-add-to-cart">
                                        <a href="#" class="wishlist-add-to-cart" 
                                        data-id="{{ $item->product->id }}" 
                                        data-wishlist-id="{{ $item->product->id }}">Add to Cart</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="wishlist-share">
                                            <h4 class="wishlist-share-title">Share on:</h4>
                                            <ul>
                                                <li><a class="facebook" href="#"></a></li>
                                                <li><a class="twitter" href="#"></a></li>
                                                <li><a class="pinterest" href="#"></a></li>
                                                <li><a class="googleplus" href="#"></a></li>
                                                <li><a class="email" href="#"></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <h3>Your wishlist is empty!</h3>
                        <a href="{{ route('shop') }}" class="btn btn-default login-btn mt-3">Browse Products</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Newsletter --}}
<div class="contact-area ptb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mar_b-30">
                <div class="contuct-info text-center">
                    <h4>Sign up for news & offers!</h4>
                    <p>You may safely unsubscribe at any time</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 offset-lg-1">
                <div class="search-box">
                    <form action="#">
                        <input type="email" placeholder="Enter your email address" />
                        <button><span class="lnr lnr-envelope"></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).on('click', '.wishlist-add-to-cart', function(e) {
    e.preventDefault();
    var productId = $(this).data('id');
    var $row = $(this).closest('tr');
    
    // Add to cart
    $.post('/cart/add/' + productId, { qty: 1 }, function(res) {
        if (res.redirect) {
            window.location.href = res.redirect;
        } else {
            $('.cart-count').text(res.count);
            showToast('Added to cart!', 'success');
            
            // Remove from wishlist
            $.post('/wishlist/' + productId, { _token: $('meta[name=csrf-token]').attr('content') }, function(res2) {
                $row.fadeOut(400, function() { $(this).remove(); });
            });
        }
    });
});
</script>
@endpush

@endsection
