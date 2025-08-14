@extends('templatemahasiswa.header')

@section('title', 'Profil Mahasiswa')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-success mb-4">Profil Mahasiswa</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
            <p><strong>Asal Kampus:</strong> {{ $mahasiswa->pengajuan->user->name ?? '-' }}</p>
            <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
            <p><strong>Jurusan:</strong> {{ $mahasiswa->jurusan }}</p>
            <p><strong>Email:</strong> {{ $mahasiswa->email }}</p>
            <p><strong>Dosen Pembimbing:</strong> {{ $mahasiswa->dospem->nama_dospem ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
