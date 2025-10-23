<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px 0;
        }

        .otp-box {
            background-color: #f8f9fa;
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }

        .info {
            color: #666;
            line-height: 1.6;
            margin: 20px 0;
        }

        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            color: #999;
            font-size: 12px;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>{{ config('app.name') }}</h1>
            <p>
                @switch($purpose)
                @case('activation')
                Aktivasi OTP
                @break
                @case('2fa_activation')
                Aktivasi 2FA
                @break
                @case('2fa_login')
                Login 2FA
                @break
                @default
                Kode OTP Login
                @endswitch
            </p>
        </div>

        <div class="content">
            <p class="info">Halo,</p>

            <p class="info">
                @switch($purpose)
                @case('activation')
                Anda telah meminta untuk mengaktifkan autentikasi OTP. Gunakan kode berikut untuk menyelesaikan
                proses aktivasi:
                @break
                @case('2fa_activation')
                Anda telah meminta untuk mengaktifkan Two-Factor Authentication (2FA). Gunakan kode berikut untuk
                menyelesaikan
                proses aktivasi:
                @break
                @case('2fa_login')
                Anda sedang melakukan login dengan Two-Factor Authentication (2FA). Gunakan kode berikut untuk
                menyelesaikan proses login:
                @break
                @default
                Gunakan kode OTP berikut untuk login ke akun Anda:
                @endswitch
            </p>

            <div class="otp-box">
                <p style="margin: 0; color: #666; font-size: 14px;">Kode OTP Anda:</p>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p class="info">
                ⏰ Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>.
            </p>

            <div class="warning">
                <strong>⚠️ Peringatan Keamanan:</strong><br>
                Jangan bagikan kode ini kepada siapa pun, termasuk staf {{ config('app.name') }}.
                Kami tidak akan pernah meminta kode OTP Anda.
            </div>

            <p class="info">
                Jika Anda tidak meminta kode ini, abaikan email ini atau hubungi administrator jika Anda merasa ada
                aktivitas mencurigakan pada akun Anda.
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem {{ config('app.name') }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>