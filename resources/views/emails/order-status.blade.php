<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #1a1a2e; color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .order-id { font-size: 20px; font-weight: bold; color: #4F46E5; margin-bottom: 20px; }
        .status-box { text-align: center; padding: 30px; background: #f8f9ff; border-radius: 8px; margin: 20px 0; }
        .status-new { font-size: 28px; font-weight: bold; color: #4F46E5; }
        .status-old { font-size: 14px; color: #999; text-decoration: line-through; margin-top: 8px; }
        .info-box { background: #f8f8f8; border-radius: 6px; padding: 16px; margin: 20px 0; font-size: 14px; color: #555; }
        .info-box p { margin: 6px 0; }
        .footer { background: #f8f8f8; padding: 20px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Grand Furniture</h1>
        <p style="margin:8px 0 0;opacity:.8;">Order Status Update</p>
    </div>
    <div class="body">
        <div class="order-id">
            Order #GF-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </div>
        <p>Dear <strong>{{ $order->name }}</strong>,</p>
        <p>Your order status has been updated.</p>

        <div class="status-box">
            <div class="status-new">{{ ucfirst($order->status) }}</div>
            <div class="status-old">Previously: {{ ucfirst($oldStatus) }}</div>
        </div>

        <div class="info-box">
            @if($order->status === 'shipped')
                <p>🚚 Your order is on the way! Expected delivery in 2-3 business days.</p>
            @elseif($order->status === 'delivered' || $order->status === 'completed')
                <p>✅ Your order has been delivered. Thank you for shopping with us!</p>
            @elseif($order->status === 'cancelled')
                <p>❌ Your order has been cancelled. If you have questions, please contact us.</p>
            @elseif($order->status === 'processing')
                <p>⚙️ Your order is being prepared and will be shipped soon.</p>
            @else
                <p>We'll keep you updated on your order progress.</p>
            @endif
        </div>

        <div class="info-box">
            <p><strong>Order Total:</strong> LKR {{ number_format($order->total, 2) }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->address ?? '—' }}</p>
            <p><strong>Phone:</strong> {{ $order->phone ?? '—' }}</p>
        </div>

        <p>Thank you for shopping with <strong>Grand Furniture</strong>!</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Grand Furniture. All rights reserved.</p>
        <p>This email was sent to {{ $order->email }}</p>
    </div>
</div>
</body>
</html>