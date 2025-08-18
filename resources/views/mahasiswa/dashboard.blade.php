@extends('templatemahasiswa.header')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <h3 class="fw-bold text-success mb-3">Selamat Datang, {{ auth()->user()->name }}</h3>
    <p class="text-muted">Ini adalah ringkasan kegiatan magang kamu di PTPN IV Regional IV</p>

    {{-- Periode Magang --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body text-center">
            <h5 class="fw-bold text-success">Periode Magang</h5>
            @if($periodeMulai && $periodeSelesai)
                <p class="mb-0">
                    {{ \Carbon\Carbon::parse($periodeMulai)->locale('id')->translatedFormat('d F Y') }}
                    –
                    {{ \Carbon\Carbon::parse($periodeSelesai)->locale('id')->translatedFormat('d F Y') }}
                </p>
            @else
                <p class="text-muted mb-0">Belum ada periode magang</p>
            @endif
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="text-success">Hadir</h5>
                    <h3>{{ $totalHadir }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="text-warning">Izin</h5>
                    <h3>{{ $totalIzin }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="text-danger">Sakit</h5>
                    <h3>{{ $totalSakit }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="text-primary">Laporan</h5>
                    <h3>{{ $totalLaporan }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Pembimbing --}}
    <div class="card shadow-sm border-0 rounded-3 mt-4">
        <div class="card-body">
            <h5 class="fw-bold">Pembimbing Lapangan</h5>
            <p>{{ $pembimbing }}</p>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('mahasiswa.absensi') }}" class="btn btn-success">
            📅 Isi Absensi
        </a>
        <a href="{{ route('mahasiswa.laporan.index') }}" class="btn btn-primary ml-3">
            📑 Laporan Kegiatan
        </a>
    </div>

</div>
@endsection
