<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - PTPN IV Regional IV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #eef7ee, #d6f5d6);
            font-family: 'Segoe UI', sans-serif;
        }

        .register-box {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 700px;
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
            margin-top: 1.5rem;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container py-5 d-flex justify-content-center align-items-center">
        <div class="register-box">
            <div class="text-center mb-4">
                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nmJ8vS3qrBIAo-Vf48vC4M-dL8TrT8rSjWtBWCJV9Y8zE=s900-c-k-c0x00ffffff-no-rj" class="ptpn-logo mb-2" alt="Logo PTPN IV">
                <h2 class="brand-header">Registrasi Akun Kampus</h2>
                <p class="text-muted">PTPN IV Regional IV</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <h5 class="mt-4">🧾 Informasi Akun Kampus</h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <h5 class="mt-4">👤 Contact Person (PIC)</h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nama PIC</label>
                        <input type="text" name="namecp" value="{{ old('namecp') }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Email PIC</label>
                        <input type="email" name="emailcp" value="{{ old('emailcp') }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Nomor HP PIC</label>
                        <input type="text" name="nohpcp" value="{{ old('nohpcp') }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Jabatan PIC</label>
                        <input type="text" name="jabatancp" value="{{ old('jabatancp') }}" class="form-control">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-green">Register</button>
                </div>
            </form>

            <p class="mt-3 text-center">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
