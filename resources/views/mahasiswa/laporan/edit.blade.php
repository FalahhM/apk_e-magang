@extends('templatemahasiswa.header')

@section('title', 'Edit Laporan Kegiatan Magang')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Edit Laporan</h3>

    <form action="{{ route('mahasiswa.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan</label>
            <input type="date" class="form-control" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $laporan->tanggal_kegiatan) }}" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" rows="3" required>{{ old('keterangan', $laporan->keterangan) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foto_dokumentasi" class="form-label">Foto Dokumentasi (opsional)</label><br>
            @if($laporan->foto_dokumentasi)
                <img src="{{ asset('storage/'.$laporan->foto_dokumentasi) }}" width="100" class="mb-2"><br>
            @endif
            <input type="file" class="form-control" name="foto_dokumentasi">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('mahasiswa.laporan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
