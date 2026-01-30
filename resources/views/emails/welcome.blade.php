<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Mayfair</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #ffffff;
            background-color: #0A0A0A;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .email-wrapper {
            background-color: #1A1A1A;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #333333;
        }
        .header {
            background-color: #0A0A0A;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #333333;
        }
        .logo {
            display: inline-block;
            background-color: #E8B923;
            color: #0A0A0A;
            font-size: 28px;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #ffffff;
            font-size: 28px;
            margin: 0 0 10px 0;
            text-align: center;
        }
        .subtitle {
            color: #9CA3AF;
            font-size: 16px;
            text-align: center;
            margin: 0 0 30px 0;
        }
        .info-box {
            background-color: #0A0A0A;
            border: 1px solid #333333;
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #333333;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #9CA3AF;
            font-size: 14px;
        }
        .info-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }
        .message {
            color: #D1D5DB;
            font-size: 15px;
            line-height: 1.8;
            margin: 20px 0;
            text-align: center;
        }
        .features {
            margin: 30px 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin: 16px 0;
            padding: 16px;
            background-color: #0A0A0A;
            border-radius: 8px;
            border: 1px solid #333333;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 12px;
        }
        .feature-text {
            color: #D1D5DB;
            font-size: 14px;
        }
        .footer {
            background-color: #0A0A0A;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #333333;
            color: #6B7280;
            font-size: 13px;
        }
        .footer-links {
            margin: 15px 0;
        }
        .footer-link {
            color: #E8B923;
            text-decoration: none;
            margin: 0 10px;
        }
        .social-icons {
            margin: 20px 0;
        }
        .social-icon {
            display: inline-block;
            margin: 0 8px;
            color: #6B7280;
            font-size: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <div class="logo">MAYFAIR</div>
            </div>
            
            <div class="content">
                <div class="welcome-icon">🎉</div>
                
                <h1>Welcome to Mayfair!</h1>
                <p class="subtitle">Your registration has been completed successfully</p>
                
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Visitor Type</span>
                        <span class="info-value">{{ ucfirst($visitorType) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobile</span>
                        <span class="info-value">{{ $mobile }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Registration Time</span>
                        <span class="info-value">{{ now()->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
                
                <p class="message">
                    Thank you for registering with Mayfair Visitor Management System. 
                    We're excited to have you here!
                </p>
                
                <div class="features">
                    <div class="feature-item">
                        <span class="feature-icon">✅</span>
                        <span class="feature-text">Your registration details have been recorded and verified</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🔒</span>
                        <span class="feature-text">Your information is secure and will be used only for visit management</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">📱</span>
                        <span class="feature-text">You'll receive notifications for your future visits</span>
                    </div>
                </div>
                
                <p class="message">
                    If you have any questions or need assistance, please feel free to contact our reception.
                </p>
            </div>
            
            <div class="footer">
                <p>
                    <strong style="color: #ffffff;">Mayfair Visitor Management System</strong><br>
                    Making your visit experience seamless and secure
                </p>
                
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Contact Us</a>
                </div>
                
                <p style="margin-top: 20px; color: #6B7280; font-size: 12px;">
                    © {{ date('Y') }} Mayfair. All rights reserved.<br>
                    This is an automated email, please do not reply.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
