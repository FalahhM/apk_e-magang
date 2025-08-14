@extends('templatedospem.header')
@section('title', 'Dashboard Dospem')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0" style="color:#1bbf6c">PTPN IV Regional IV</h3>
            <small class="text-muted">Dashboard Dosen Pembimbing</small>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill px-3 py-2" style="background:#e8fff4;color:#16a34a;border:1px solid #a7f3d0">
                Tahun {{ $year }}
            </span>
        </div>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Mahasiswa Bimbingan</div>
                            <div class="fs-3 fw-bold">{{ $totalMahasiswa }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:#e8fff4">
                            <i class="bi bi-people" style="color:#16a34a"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Laporan</div>
                            <div class="fs-3 fw-bold">{{ $totalLaporan }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:#eef2ff">
                            <i class="bi bi-file-earmark-text" style="color:#6366f1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Hadir</div>
                            <div class="fs-3 fw-bold">{{ $totalHadir }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:#e8fff4">
                            <i class="bi bi-check2-circle" style="color:#16a34a"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Izin & Alfa</div>
                            <div class="fs-3 fw-bold">{{ $totalIzin + $totalAlfa }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:#fff7ed">
                            <i class="bi bi-exclamation-circle" style="color:#f59e0b"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi / Pengingat --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="bi bi-bell me-1"></i> Belum Absen Hari Ini</h6>
                    @if($belumAbsenHariIni->isEmpty())
                        <div class="alert alert-success mb-0 py-2">Semua mahasiswa sudah absen hari ini. 👍</div>
                    @else
                        <ul class="mb-0">
                            @foreach($belumAbsenHariIni as $mhs)
                                <li>{{ $mhs->nama_mahasiswa }} <span class="text-muted">({{ $mhs->nim }})</span></li>
                            @endforeach
                        </ul>
                        <small class="text-muted">Tampilkan maksimal 6 nama.</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="bi bi-bell me-1"></i> Belum Kirim Laporan Minggu Ini</h6>
                    @if($belumLaporMingguIni->isEmpty())
                        <div class="alert alert-success mb-0 py-2">Semua mahasiswa sudah kirim laporan minggu ini. ✅</div>
                    @else
                        <ul class="mb-0">
                            @foreach($belumLaporMingguIni as $mhs)
                                <li>{{ $mhs->nama_mahasiswa }} <span class="text-muted">({{ $mhs->nim }})</span></li>
                            @endforeach
                        </ul>
                        <small class="text-muted">Tampilkan maksimal 6 nama.</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body" style="height:260px;"> {{-- tinggi lebih ramping --}}
                    <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-1"></i> Absensi per Bulan ({{ $year }})</h6>
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body" style="height:220px;"> {{-- lebih pendek dari kiri --}}
                    <h6 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-1"></i> Laporan per Bulan ({{ $year }})</h6>
                    <canvas id="reportChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('dospem.absensi') }}" class="btn btn-success mr-3">
                <i class="bi bi-journal-check me-1"></i> Lihat Data Absensi
            </a>
            <a href="{{ route('dospem.laporan') }}" class="btn btn-outline-success">
                <i class="bi bi-file-text me-1"></i> Lihat Data Laporan
            </a>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsBulan   = @json($labelsBulan);
const hadirData     = @json($hadirPerBulan);
const izinData      = @json($izinPerBulan);
const alfaData      = @json($alfaPerBulan);
const laporanData   = @json($laporanPerBulan);

new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
        labels: labelsBulan,
        datasets: [
            { label: 'Hadir', data: hadirData, borderWidth: 1 },
            { label: 'Izin',  data: izinData,  borderWidth: 1 },
            { label: 'Alfa',  data: alfaData,  borderWidth: 1 },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        plugins: { legend: { position: 'bottom' } }
    }
});

new Chart(document.getElementById('reportChart'), {
    type: 'line',
    data: {
        labels: labelsBulan,
        datasets: [
            { label: 'Jumlah Laporan', data: laporanData, tension: 0.3, borderWidth: 2, pointRadius: 3 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // tinggi ikut container 220px
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
