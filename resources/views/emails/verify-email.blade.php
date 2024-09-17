<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        a {
            color: #ffffff;
            background-color: #4CAF50;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        a:hover {
            background-color: #45a049;
        }
        .footer {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verifikasi Email</h1>
        <p>Halo {{ $user->name }},</p>
        <p>Terima kasih telah mendaftar di aplikasi kami. Untuk melanjutkan, silakan klik tautan di bawah ini untuk memverifikasi email Anda:</p>
        <p>
            <a href="{{ url('/email/verify/' . $user->id . '/' . $user->email_verification_token) }}">Verifikasi Email</a>
        </p>
        <p class="footer">Jika Anda tidak melakukan pendaftaran, Anda bisa mengabaikan email ini.</p>
    </div>
</body>
</html>
