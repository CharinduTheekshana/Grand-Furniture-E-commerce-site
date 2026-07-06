<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; background: #fff; }

    .invoice-wrapper { padding: 40px; max-width: 800px; margin: 0 auto; }

    /* Header */
    .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 3px solid #c8a96e; padding-bottom: 20px; }
    .company-name { font-size: 28px; font-weight: 700; color: #c8a96e; letter-spacing: 2px; }
    .company-tagline { font-size: 11px; color: #999; margin-top: 4px; }
    .invoice-title { text-align: right; }
    .invoice-title h2 { font-size: 22px; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 3px; }
    .invoice-number { font-size: 13px; color: #666; margin-top: 4px; }
    .invoice-date { font-size: 12px; color: #999; margin-top: 2px; }

    /* Info Section */
    .invoice-info { display: flex; justify-content: space-between; margin-bottom: 35px; gap: 30px; }
    .info-box { flex: 1; }
    .info-box h4 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #c8a96e; margin-bottom: 10px; border-bottom: 1px solid #f0f0f0; padding-bottom: 6px; }
    .info-box p { font-size: 12px; color: #555; line-height: 1.8; }
    .info-box strong { color: #333; }

    /* Status Badge */
    .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
    .status-pending    { background: #fef3c7; color: #92400e; }
    .status-completed  { background: #d1fae5; color: #065f46; }
    .status-processing { background: #ede9fe; color: #4c1d95; }
    .status-shipped    { background: #dbeafe; color: #1e40af; }
    .status-delivered  { background: #d1fae5; color: #065f46; }
    .status-cancelled  { background: #fee2e2; color: #991b1b; }
    .status-paid       { background: #dbeafe; color: #1e40af; }

    /* Items Table */
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    .items-table thead tr { background: #c8a96e; color: #fff; }
    .items-table thead th { padding: 12px 14px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
    .items-table thead th:last-child { text-align: right; }
    .items-table tbody tr { border-bottom: 1px solid #f0f0f0; }
    .items-table tbody tr:nth-child(even) { background: #fafafa; }
    .items-table tbody td { padding: 12px 14px; font-size: 13px; vertical-align: middle; }
    .items-table tbody td:last-child { text-align: right; font-weight: 600; }
    .items-table .product-name { font-weight: 600; color: #333; }
    .items-table .product-sku  { font-size: 11px; color: #999; }

    /* Totals */
    .totals-section { display: flex; justify-content: flex-end; margin-bottom: 40px; }
    .totals-table { width: 280px; }
    .totals-table tr td { padding: 7px 0; font-size: 13px; }
    .totals-table tr td:first-child { color: #666; }
    .totals-table tr td:last-child { text-align: right; font-weight: 600; }
    .totals-table .total-row td { border-top: 2px solid #c8a96e; padding-top: 10px; font-size: 16px; font-weight: 700; color: #c8a96e; }

    /* Footer */
    .invoice-footer { border-top: 1px solid #f0f0f0; padding-top: 20px; text-align: center; }
    .invoice-footer p { font-size: 11px; color: #999; line-height: 1.7; }
    .thank-you { font-size: 16px; font-weight: 700; color: #c8a96e; margin-bottom: 8px; }

    /* Watermark for cancelled */
    .watermark { position: fixed; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 80px; font-weight: 900; color: rgba(231, 76, 60, 0.08); text-transform: uppercase; letter-spacing: 10px; pointer-events: none; z-index: 0; }
</style>
</head>
<body>

@if($order->status === 'cancelled')
<div class="watermark">CANCELLED</div>
@endif

<div class="invoice-wrapper">

    {{-- Header --}}
    <div class="invoice-header">
        <div>
            <div class="company-name">GRAND</div>
            <div class="company-tagline">Premium Furniture & Home Décor</div>
            <div style="margin-top:10px;font-size:11px;color:#999;line-height:1.7;">
                Grand Furniture Pvt Ltd<br>
                Colombo, Sri Lanka<br>
                info@grandfurniture.lk
            </div>
        </div>
        <div class="invoice-title">
            <h2>Invoice</h2>
            <div class="invoice-number">#GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="invoice-date">Date: {{ $order->created_at->format('d M Y') }}</div>
            <div style="margin-top:8px;">
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Bill To + Order Info --}}
    <div class="invoice-info">
        <div class="info-box">
            <h4>Bill To</h4>
            <p>
                <strong>{{ $order->name }}</strong><br>
                {{ $order->address }}<br>
                @if($order->phone)Phone: {{ $order->phone }}<br>@endif
                Email: {{ $order->email }}
            </p>
        </div>
        <div class="info-box">
            <h4>Order Details</h4>
            <p>
                <strong>Order ID:</strong> #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}<br>
                <strong>Payment:</strong> {{ ucfirst($order->status) }}<br>
                @if($order->notes)
                <strong>Notes:</strong> {{ $order->notes }}
                @endif
            </p>
        </div>
    </div>

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            @php $lineTotal = $item->price * $item->quantity; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="product-name">{{ $item->product->name ?? 'Product Unavailable' }}</div>
                    @if($item->product)
                    <div class="product-sku">SKU: {{ strtoupper(substr(str_replace('-','',$item->product->slug ?? ''),0,8)) }}</div>
                    @endif
                </td>
                <td>LKR {{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>LKR {{ number_format($lineTotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    @php
        $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
        $shipping = $order->total - $subtotal;
        if ($shipping < 0) $shipping = 0;
    @endphp
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>LKR {{ number_format($subtotal, 2) }}</td>
            </tr>
            @if($shipping > 0)
            <tr>
                <td>Shipping</td>
                <td>LKR {{ number_format($shipping, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total</td>
                <td>LKR {{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="invoice-footer">
        <div class="thank-you">Thank you for shopping with Grand Furniture!</div>
        <p>
            This is a computer-generated invoice and does not require a signature.<br>
            For any queries, please contact us at info@grandfurniture.lk<br>
            Grand Furniture Pvt Ltd — Colombo, Sri Lanka
        </p>
    </div>

</div>
</body>
</html>