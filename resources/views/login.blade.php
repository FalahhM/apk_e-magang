<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PTPN IV Regional IV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #eef7ee, #d6f5d6);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }

        .brand-header {
            color: #2e7d32;
        }

        .btn-green {
            background-color: #388e3c;
            color: white;
        }

        .btn-green:hover {
            background-color: #2e7d32;
        }

        .ptpn-logo {
            height: 100px;
        }

        .footer {
            margin-top: 2rem;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="login-box text-center">
            <img src="https://lms.reg5palmco.com/moodle/pluginfile.php/1/theme_moove/logo/1707130331/Logo%20N4.png" alt="Logo PTPN IV" class="ptpn-logo mb-3">
            <h2 class="brand-header mb-3">E-Magang</h2>
            <h2 class="brand-header mb-3">PTPN IV Regional IV</h2>
            <p class="text-muted">Silakan login untuk melanjutkan</p>

            @if($errors->any())
                <div class="alert alert-danger text-start">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="text-start mt-4">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-green">Login</button>
                </div>
            </form>

            <p class="mt-3">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            
        </div>
    </div>
</body>
</html>
