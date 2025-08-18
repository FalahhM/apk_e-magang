@extends('templateadmin.header')
@section('content')

<div class="container mt-4">
    <h3>Edit Absensi Mahasiswa</h3>
    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $absensi->mahasiswa->nama_mahasiswa }}" readonly>
        </div>

        <div class="mb-3">
            <label>Status Kehadiran</label>
            <select name="status" class="form-control">
                @foreach(['Hadir','Izin','Sakit','Alfa'] as $s)
                    <option value="{{ $s }}" {{ ucfirst($absensi->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" value="{{ $absensi->keterangan }}">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection
