<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .code-container {
            text-align: center;
            margin: 30px 0;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #4a6ee0;
            padding: 10px 20px;
            background-color: #e8eeff;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Email Verification</h2>
        </div>
        
        <p>Hello {{ $user->name }},</p>
        
        <p>Thank you for registering with our service. Please use the verification code below to verify your email address:</p>
        
        <div class="code-container">
            <div class="verification-code">{{ $code }}</div>
        </div>
        
        <p>This code will expire in 60 minutes.</p>
        
        <p>If you did not request this verification, please ignore this email.</p>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
