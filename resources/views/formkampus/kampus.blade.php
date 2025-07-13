@extends('template.header')
@section('content')

<style>
    body {
        background-color: #eef7ee;
    }

    .card-header {
        background-color: #388e3c;
        color: white;
        font-weight: 600;
    }

    .btn-info {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }

    .btn-info:hover {
        background-color: #256428;
        border-color: #256428;
    }

    h2, h4 {
        color: #2e7d32;
        font-weight: bold;
    }

    label {
        font-weight: 500;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center">Form Data Kampus</h2>

    <div class="card mb-4 shadow">
        <div class="card-header">📘 Informasi Kampus</div>
        <div class="card-body">
            <form action="{{ url('/mahasiswa') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="asalKampus" class="form-label">Asal Kampus</label>
                    <input type="text" class="form-control" id="asalKampus" value="{{ $user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="noTelp" class="form-label">No Telp</label>
                    <input type="tel" class="form-control" id="noTelp" value="{{ $user->no_telp }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="alamatKampus" class="form-label">Alamat Kampus</label>
                    <textarea class="form-control" id="alamatKampus" rows="3" readonly>{{ $user->alamat }}</textarea>
                </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">👤 Contact Person</div>
        <div class="card-body">
                <div class="mb-3">
                    <label for="namaCP" class="form-label">Nama Contact Person</label>
                    <input type="text" class="form-control" id="namaCP" value="{{ $contact_person->namecp }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="emailCP" class="form-label">Email Contact Person</label>
                    <input type="email" class="form-control" id="emailCP" value="{{ $contact_person->emailcp }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="noHpCP" class="form-label">No HP Contact Person</label>
                    <input type="tel" class="form-control" id="noHpCP" value="{{ $contact_person->nohpcp }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="jabatanCP" class="form-label">Jabatan Contact Person</label>
                    <input type="text" class="form-control" id="jabatanCP" value="{{ $contact_person->jabatan }}" readonly>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
