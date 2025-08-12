@extends('templateadmin.header')
@section('content')

<div class="container mt-4">
    <h3 class="mb-4 text-success">Rekap Absensi Mahasiswa</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="kampus_id">Filter Kampus</label>
            <select name="kampus_id" class="form-control">
                <option value="">Semua Kampus</option>
                @foreach($listKampus as $kampus)
                    <option value="{{ $kampus->id }}" {{ request('kampus_id') == $kampus->id ? 'selected' : '' }}>
                        {{ $kampus->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Nama Mahasiswa</label>
            <select name="mahasiswa_id" class="form-control">
                <option value="">Semua</option>
                @foreach($mahasiswaList as $mhs)
                    <option value="{{ $mhs->id }}" {{ request('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                        {{ $mhs->nama_mahasiswa }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <a href="{{ route('absensi.exportALLPDF', request()->query()) }}" class="btn btn-outline-success w-100 shadow-sm fw-semibold">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Rekap PDF Semua Mahasiswa
            </a>
        </div>

    </form>
    
    @forelse($absensiData as $mhsId => $absens)
        @php
            $mahasiswa = $absens->first()->mahasiswa ?? null;
            $hadir = $absens->where('status', 'hadir')->count();
            $izin = $absens->where('status', 'izin')->count();
            $sakit = $absens->where('status', 'sakit')->count();
            $alfa  = $absens->where('status', 'alfa')->count();
        @endphp

        @if($mahasiswa)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $mahasiswa->nama_mahasiswa }} ({{ $mahasiswa->nim }})</h5>
                <p class="mb-1">Jurusan: {{ $mahasiswa->jurusan }}</p>
                <p class="mb-0">Asal Kampus: {{ $mahasiswa->kampus->name ?? '-' }}</p>
                
                <div class="row text-center">
                    <div class="col">✅ Hadir: <strong>{{ $hadir }}</strong></div>
                    <div class="col">🟡 Izin: <strong>{{ $izin }}</strong></div>
                    <div class="col">🔵 Sakit: <strong>{{ $sakit }}</strong></div>
                    <div class="col">❌ Alfa: <strong>{{ $alfa }}</strong></div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('absensi.export', $mahasiswa->id) }}" class="btn btn-sm btn-outline-success">
                        📄 Download Rekap PDF
                    </a>
                </div>

            </div>
        </div>
        @endif
    @empty
        <div class="alert alert-warning">Tidak ada data absensi ditemukan.</div>
    @endforelse
</div>
@endsection
