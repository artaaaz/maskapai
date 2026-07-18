<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
        }
        .email-header span {
            color: #facc15;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #1e293b;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        .email-body p {
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
            margin: 0 0 20px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            margin: 10px 0 20px 0;
        }
        .btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }
        .email-footer {
            padding: 30px;
            background: #f8fafc;
            text-align: center;
        }
        .email-footer p {
            color: #94a3b8;
            font-size: 13px;
            margin: 0;
        }
        .email-footer a {
            color: #64748b;
            text-decoration: underline;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 20px 0;
        }
        .note {
            background: #fef9c3;
            border: 1px solid #fde047;
            border-radius: 12px;
            padding: 15px;
            font-size: 13px;
            color: #854d0e;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        {{-- Header --}}
        <div class="email-header">
            <h1>drg<span>.</span>Maskapai</h1>
        </div>

        {{-- Body --}}
        <div class="email-body">
            <h2>Halo {{ $user->name ?? 'Pengguna' }},</h2>
            
            <p>Terima kasih telah mendaftar di <strong>drgMaskapai</strong>! Kami senang menyambut Anda.</p>
            
            <p>Silakan klik tombol di bawah ini untuk memverifikasi akun Anda:</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl ?? '#' }}" class="btn">
                    Verifikasi Email
                </a>
            </div>

            <p>Tautan verifikasi ini akan kedaluwarsa dalam waktu <strong>60 menit</strong>.</p>

            <p>Jika Anda tidak merasa membuat akun ini, abaikan email ini.</p>

            <div class="note">
                <strong>💡 Perlu bantuan?</strong> Jika Anda mengalami kesulitan menekan tombol di atas, salin dan tempel URL berikut ke browser Anda:<br>
                <a href="{{ $verificationUrl ?? '#' }}" style="color: #2563eb; word-break: break-all;">{{ $verificationUrl ?? '#' }}</a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="email-footer">
            <p>Salam hangat,</p>
            <p style="font-weight: 700; color: #1e293b; margin-bottom: 15px;">Tim drgMaskapai</p>
            <div class="divider"></div>
            <p>&copy; {{ date('Y') }} drgMaskapai. Seluruh hak cipta dilindungi.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>