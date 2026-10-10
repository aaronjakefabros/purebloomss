<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .header { background-color: #b0415d; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f8f9fa; }
        .total { font-weight: bold; font-size: 1.2em; color: #b0415d; }
        .footer { text-align: center; padding: 20px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌸 Pureblooms</h1>
        <p>Order Receipt</p>
    </div>
    <div class="content">
        <p>Hello {{ $order->customer_email }},</p>
        <p>Thank you for your order! Here are your order details:</p>
        
        <p><strong>Order ID:</strong> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
        <p><strong>Delivery Method:</strong> {{ $order->delivery_method }}</p>
        @if($order->delivery_method == 'Delivery')
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Add-ons</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->color ?? 'N/A' }}</td>
                    <td>{{ $item->addons ?? 'None' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            <p>Subtotal: ₱{{ number_format($order->total_amount - $order->delivery_fee, 2) }}</p>
            <p>Delivery Fee: ₱{{ number_format($order->delivery_fee, 2) }}</p>
            <p class="total">Total Paid: ₱{{ number_format($order->total_amount, 2) }}</p>
        </div>

        <p style="margin-top: 30px;">If you have any questions, reply to this email or contact us via Messenger.</p>
    </div>
    <div class="footer">
        <p>© 2026 Pureblooms. All rights reserved.</p>
    </div>
</body>
</html>