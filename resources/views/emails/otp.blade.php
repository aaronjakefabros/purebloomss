<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fffcf9; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .otp-code { font-size: 32px; font-weight: bold; color: #b0415d; letter-spacing: 5px; text-align: center; margin: 20px 0; padding: 15px; background: #f8e1e7; border-radius: 8px; }
        .footer { margin-top: 30px; font-size: 12px; color: #6c757d; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #b0415d; text-align: center;">🌸 Pureblooms</h2>
        <p>Hello {{ $userName }},</p>
        <p>Thank you for registering! Please use the One-Time Password (OTP) below to verify your email address:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p style="text-align: center; color: #6c757d;">This code will expire in 10 minutes.</p>
        
        <div class="footer">
            <p>If you did not create an account, please ignore this email.</p>
            <p>© 2026 Pureblooms. All rights reserved.</p>
        </div>
    </div>
</body>
</html>