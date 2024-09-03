<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Register</title>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-center">
            <div class="w-50 border rounded px-3 py-3">
                <h1 class="text-center">Register</h1>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <h4 class="mt-5 mb-4">Akun Kampus</h4>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" value="{{ old('name') }}" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" value="{{ old('email') }}" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" value="{{ old('alamat') }}" name="alamat" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                        <input type="text" value="{{ old('no_telp') }}" name="no_telp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <h4 class="mt-4 mb-4">Contact Person (PIC)</h4>
                    <div class="mb-3">
                        <label for="namecp" class="form-label">Nama</label>
                        <input type="text" value="{{ old('namecp') }}" name="namecp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="emailcp" class="form-label">Email</label>
                        <input type="email" value="{{ old('emailcp') }}" name="emailcp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="nohpcp" class="form-label">Nomor Hp</label>
                        <input type="text" value="{{ old('nohpcp') }}" name="nohpcp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="jabatancp" class="form-label">Jabatan</label>
                        <input type="text" value="{{ old('jabatancp') }}" name="jabatancp" class="form-control">
                    </div>
                    <div class="mb-3 d-grid">
                        <button name="submit" type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
