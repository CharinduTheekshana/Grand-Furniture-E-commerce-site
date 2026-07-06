@if($product->is_offer_active && $product->stock > 0)
<div class="offer-badge {{ $product->offer_badge_class }}"
     @if($product->offer_end_date) data-end="{{ $product->offer_end_date->toIso8601String() }}" @endif>
    {{ $product->offer_badge }}
</div>
@if($product->offer_type === 'flash_sale' && $product->offer_end_date)
<div class="offer-countdown">
    <div class="offer-countdown-inner">
        Ends: <span class="countdown-timer" data-end="{{ $product->offer_end_date->toIso8601String() }}">
            --:--:--:--
        </span>
    </div>
</div>
@endif
@endif