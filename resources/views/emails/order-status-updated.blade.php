<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .header { background-color: #b0415d; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; }
        .info-box { background-color: #f8f9fa; border-left: 4px solid #b0415d; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 0.9em; color: #777; border-top: 1px solid #eee; margin-top: 30px; }
        .btn { display: inline-block; background-color: #b0415d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌸 Pureblooms</h1>
        <p>Order Status Update</p>
    </div>
    
    <div class="content">
        <p>Hello {{ $order->customer_email }},</p>
        
        <p>Great news! Your bouquet is now ready.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #b0415d;">
                @if($order->delivery_method == 'Pickup')
                    🏪 Ready for Store Pick-up!
                @else
                    🚚 Out for Delivery!
                @endif
            </h3>
            <p><strong>Order ID:</strong> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            
            @if($order->delivery_method == 'Pickup')
                <p><strong>Action Needed:</strong> Please visit our store to claim your order. Don't forget to bring this email or your Order ID.</p>
                <p><strong>Store Hours:</strong> 9:00 AM - 6:00 PM</p>
            @else
                <p><strong>Delivery Details:</strong> Your order will be delivered to your provided address on <strong>{{ $order->delivery_date }}</strong> between <strong>{{ $order->delivery_time }}</strong>.</p>
                <p>Please ensure someone is available to receive the bouquet.</p>
            @endif
        </div>

        <p>If you have any questions or need to reschedule, please reply to this email or contact us via our Messenger page.</p>
        
        <a href="https://m.me/pureblooms" class="btn">Contact Us on Messenger</a>
    </div>

    <div class="footer">
        <p>© 2026 Pureblooms. All rights reserved.</p>
        <p>Fresh flowers, thoughtfully arranged, delivered with love.</p>
    </div>
</body>
</html>