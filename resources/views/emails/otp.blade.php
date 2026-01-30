<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .otp-code {
            background: #F3F4F6;
            border: 2px dashed #4F46E5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-number {
            font-size: 36px;
            font-weight: bold;
            color: #4F46E5;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .info {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏢 Mayfair VMS</div>
            <h2 style="color: #1F2937; margin: 0;">Verification Code</h2>
        </div>

        @if($name)
        <p style="font-size: 16px;">Hello <strong>{{ $name }}</strong>,</p>
        @else
        <p style="font-size: 16px;">Hello,</p>
        @endif

        <p>Thank you for registering with Mayfair Visitor Management System. Please use the verification code below to complete your registration:</p>

        <div class="otp-code">
            <p style="margin: 0; color: #6B7280; font-size: 14px;">Your Verification Code</p>
            <div class="otp-number">{{ $otp }}</div>
            <p style="margin: 0; color: #6B7280; font-size: 14px;">⏰ Valid for {{ $expiryMinutes }} minutes</p>
        </div>

        <div class="info">
            <strong>🔒 Security Note:</strong> For your security, please do not share this code with anyone. Mayfair staff will never ask for your verification code.
        </div>

        <p>If you didn't request this code, please ignore this email.</p>

        <div class="footer">
            <p><strong>Mayfair - Where Excellence Meets Service</strong></p>
            <p style="font-size: 12px; color: #9CA3AF;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
