<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Akun Anda - {{ $appName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #eeeeee;
        }

        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333333;
        }

        .content {
            padding: 20px 0;
            line-height: 1.6;
        }

        .status-box {
            padding: 20px;
            text-align: center;
            margin: 25px 0;
            border-radius: 8px;
        }

        .status-box.activated {
            background-color: #e9f7ef;
            border: 2px dashed #28a745;
        }

        .status-box.deactivated {
            background-color: #fbe9e7;
            border: 2px dashed #dc3545;
        }

        .status-text {
            font-size: 20px;
            font-weight: bold;
        }

        .status-text.activated-text {
            color: #28a745;
        }

        .status-text.deactivated-text {
            color: #dc3545;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eeeeee;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="icon">{{ $user->active == 1 ? '✅' : '🔒' }}</div>
            <h1>Akun Telah {{ $status }}</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->nama }}</strong>,</p>
            <p>Kami memberitahukan bahwa status akun Anda di <strong>{{ $appName }}</strong> telah diperbarui.</p>
            <div class="status-box {{ $user->active == 1 ? 'activated' : 'deactivated' }}">
                <p style="margin: 0; font-size: 14px;">Status Akun Anda Saat Ini:</p>
                <div class="status-text {{ $user->active == 1 ? 'activated-text' : 'deactivated-text' }}">
                    {{ $user->active == 1 ? 'AKTIF' : 'NONAKTIF' }}
                </div>
            </div>
            <p>
                Sebagai hasilnya, Anda sekarang <strong>{{ $dapatLogin }}</strong>
                login menggunakan kredensial Anda.
            </p>
            <p>Jika Anda memiliki pertanyaan atau merasa ini adalah sebuah kesalahan, silakan hubungi administrator desa.</p>
            <p>Terima kasih.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem {{ $appName }}.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>