<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #fe742b 0%, #ff8c4d 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .field {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .field:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #fe742b;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .value {
            color: #333;
            font-size: 16px;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #fe742b;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .timestamp {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
        </div>

        <div class="timestamp">
            <strong>Received:</strong> {{ $data['timestamp'] ?? now()->format('F j, Y, g:i a') }}
        </div>

        <div class="field">
            <span class="label">Name</span>
            <span class="value">{{ $data['name'] }}</span>
        </div>

        <div class="field">
            <span class="label">Email Address</span>
            <span class="value">
                <a href="mailto:{{ $data['email'] }}" style="color: #fe742b; text-decoration: none;">
                    {{ $data['email'] }}
                </a>
            </span>
        </div>

        @if(!empty($data['company']))
        <div class="field">
            <span class="label">Company</span>
            <span class="value">{{ $data['company'] }}</span>
        </div>
        @endif

        <div class="field">
            <span class="label">Subject</span>
            <span class="value">{{ ucfirst(str_replace('-', ' ', $data['subject'])) }}</span>
        </div>

        <div class="field">
            <span class="label">Message</span>
            <div class="message-box">{{ $data['message'] }}</div>
        </div>

        <div class="footer">
            <p>This email was sent from the contact form on <strong>arsus.nl</strong></p>
            <p>Reply directly to this email to respond to {{ $data['name'] }}</p>
        </div>
    </div>
</body>
</html>
