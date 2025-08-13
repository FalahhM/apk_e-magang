@extends('templatemahasiswa.header')

@section('title', 'Upload Laporan TTD')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Upload Laporan Kegiatan yang Sudah Ditandatangani</h3>

    <form action="{{ route('mahasiswa.laporan.uploadFinal') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file_laporan_ttd" class="form-label">File Laporan (PDF, max 2MB)</label>
            <input type="file" name="file_laporan_ttd" id="file_laporan_ttd" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
        <a href="{{ route('mahasiswa.laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
