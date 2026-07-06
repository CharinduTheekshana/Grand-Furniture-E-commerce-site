{{-- ═══ FLOATING COUPON WIDGET ═══ --}}
{{-- resources/views/components/coupon-widget.blade.php --}}

@php
    $widgetCoupons = \App\Models\Coupon::where('is_active', true)
        ->where(function($q) { $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()); })
        ->where(function($q) { $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit'); })
        ->latest()->take(5)->get();
@endphp

@if($widgetCoupons->count() > 0)

{{-- Toggle Button --}}
<div id="coupon-widget-toggle"
     onclick="toggleCouponWidget()"
     style="position:fixed;right:0;top:50%;transform:translateY(-50%);
            background:#c8a96e;color:#fff;writing-mode:vertical-rl;
            padding:14px 8px;cursor:pointer;z-index:9999;
            border-radius:8px 0 0 8px;font-size:12px;font-weight:700;
            letter-spacing:1px;box-shadow:-2px 0 8px rgba(0,0,0,0.15);
            transition:all 0.3s;">
    🎟️ COUPONS
</div>

{{-- Coupon Panel --}}
<div id="coupon-widget-panel"
     style="position:fixed;right:-300px;top:50%;transform:translateY(-50%);
            width:280px;background:#fff;z-index:9998;
            border-radius:10px 0 0 10px;
            box-shadow:-4px 0 20px rgba(0,0,0,0.15);
            transition:right 0.3s ease;overflow:hidden;">

    {{-- Panel Header --}}
    <div style="background:#c8a96e;color:#fff;padding:12px 16px;
                display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:14px;font-weight:700;letter-spacing:0.5px;">
            🎟️ Active Coupons
        </span>
        <span onclick="toggleCouponWidget()"
              style="cursor:pointer;font-size:18px;line-height:1;">&times;</span>
    </div>

    {{-- Coupon List --}}
    <div style="max-height:420px;overflow-y:auto;padding:12px;">
        @foreach($widgetCoupons as $c)
        <div style="border:1.5px dashed #c8a96e;border-radius:8px;
                    padding:12px;margin-bottom:10px;background:#fdfaf5;
                    position:relative;overflow:hidden;">

            {{-- Ribbon --}}
            <div style="position:absolute;top:8px;right:-16px;
                        background:#c8a96e;color:#fff;font-size:9px;
                        font-weight:700;padding:2px 22px;
                        transform:rotate(45deg);letter-spacing:1px;">
                {{ $c->type === 'percent' ? $c->value.'%' : 'FLAT' }}
            </div>

            {{-- Discount --}}
            <div style="font-size:22px;font-weight:800;color:#c8a96e;margin-bottom:4px;">
                {{ $c->type === 'percent' ? $c->value.'% OFF' : 'LKR '.number_format($c->value,0).' OFF' }}
            </div>

            {{-- Code --}}
            <div style="background:#fff;border:1px dashed #c8a96e;border-radius:5px;
                        padding:5px 10px;letter-spacing:2px;font-size:14px;
                        font-weight:800;color:#333;margin-bottom:6px;text-align:center;">
                {{ $c->code }}
            </div>

            {{-- Details --}}
            <div style="font-size:11px;color:#999;margin-bottom:8px;line-height:1.6;">
                @if($c->min_order > 0)
                    Min: LKR {{ number_format($c->min_order,0) }}<br>
                @endif
                @if($c->expires_at)
                    Exp: {{ $c->expires_at->format('d M Y') }}
                @endif
            </div>

            {{-- Copy Button --}}
            <button onclick="widgetCopyCode('{{ $c->code }}')"
                    style="width:100%;background:#333;color:#fff;border:none;
                           padding:6px;border-radius:4px;cursor:pointer;
                           font-size:12px;letter-spacing:0.5px;">
                📋 Copy Code
            </button>
        </div>
        @endforeach
    </div>

    {{-- Footer --}}
    <div style="padding:10px 16px;border-top:1px solid #f0f0f0;
                text-align:center;font-size:11px;color:#999;">
        Click a code to copy, then use at checkout
    </div>
</div>

<style>
#coupon-widget-panel.open {
    right: 0 !important;
}
#coupon-widget-toggle.open {
    right: 280px !important;
    border-radius: 8px 0 0 8px;
}
</style>

<script>
var couponWidgetOpen = false;

function toggleCouponWidget() {
    couponWidgetOpen = !couponWidgetOpen;
    var panel  = document.getElementById('coupon-widget-panel');
    var toggle = document.getElementById('coupon-widget-toggle');
    if (couponWidgetOpen) {
        panel.style.right  = '0';
        toggle.style.right = '280px';
    } else {
        panel.style.right  = '-300px';
        toggle.style.right = '0';
    }
}

function widgetCopyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        if (typeof showToast === 'function') {
            showToast('Coupon "' + code + '" copied! Use at checkout.', 'success');
        } else {
            alert('Copied: ' + code);
        }
    }).catch(function() {
        var el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        if (typeof showToast === 'function') {
            showToast('Coupon "' + code + '" copied!', 'success');
        }
    });
}
</script>

@endif