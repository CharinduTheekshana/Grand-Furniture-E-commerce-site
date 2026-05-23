<div style="padding:10px;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr style="background:#f8f7f4;border-bottom:2px solid #c19b5b;">
                <th style="padding:10px 8px;text-align:left;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Product</th>
                <th style="padding:10px 8px;text-align:center;text-transform:uppercase;font-size:11px;letter-spacing:1px;">ID</th>
                <th style="padding:10px 8px;text-align:center;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Qty</th>
                <th style="padding:10px 8px;text-align:right;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Unit Price</th>
                <th style="padding:10px 8px;text-align:right;text-transform:uppercase;font-size:11px;letter-spacing:1px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:10px 8px;">
                    @if($item->product)
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img src="{{ $item->product->image_url }}"
                             style="width:50px;height:50px;object-fit:cover;border:1px solid #eee;" />
                        <div>
                            <div style="font-weight:600;">{{ $item->product->name }}</div>
                            <div style="font-size:11px;color:#999;">{{ $item->product->category->name ?? '' }}</div>
                        </div>
                    </div>
                    @else
                    <span style="color:#999;">Product unavailable</span>
                    @endif
                </td>
                <td style="padding:10px 8px;text-align:center;color:#666;">#{{ $item->product_id }}</td>
                <td style="padding:10px 8px;text-align:center;font-weight:bold;">{{ $item->quantity }}</td>
                <td style="padding:10px 8px;text-align:right;">LKR {{ number_format($item->price, 2) }}</td>
                <td style="padding:10px 8px;text-align:right;font-weight:bold;">LKR {{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f8f7f4;">
                <td colspan="4" style="padding:12px 8px;text-align:right;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">Order Total:</td>
                <td style="padding:12px 8px;text-align:right;font-weight:bold;color:#c19b5b;font-size:16px;">LKR {{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>