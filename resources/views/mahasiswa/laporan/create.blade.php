@extends('templatemahasiswa.header')

@section('title', 'Tambah Laporan Kegiatan Magang')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Tambah Laporan Kegiatan Magang</h3>

    <form action="{{ route('mahasiswa.laporan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Tanggal Kegiatan</label>
            <input type="date" name="tanggal_kegiatan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keterangan Kegiatan</label>
            <textarea name="keterangan" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Foto Dokumentasi</label>
            <input type="file" name="foto_dokumentasi" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mahasiswa.laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
