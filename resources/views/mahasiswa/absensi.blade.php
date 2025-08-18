@extends('templatemahasiswa.header')

@section('title', 'Absensi Mahasiswa')

@section('content')
<style>
    /* Kartu status */
    .status-option {
        cursor: pointer;
        border-radius: 14px;
        padding: 25px;
        text-align: center;
        font-weight: bold;
        color: white;
        transition: all 0.25s ease-in-out;
        position: relative;
        user-select: none;
        overflow: hidden;
    }

    /* Gradient hover */
    .status-option:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        background-size: 200% 200%;
        animation: gradientMove 3s ease infinite;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Tanda centang di tengah saat dipilih */
    .status-option.selected::after {
        content: "✔";
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 40px;
        font-weight: bold;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 0 6px rgba(0,0,0,0.5);
        transform: translate(-50%, -50%);
        animation: popIn 0.3s ease;
    }

    @keyframes popIn {
        0% { transform: translate(-50%, -50%) scale(0.5); opacity: 0; }
        100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }

    /* Efek glow saat dipilih */
    .status-option.selected {
        box-shadow: 0 0 15px rgba(255,255,255,0.7), 0 0 30px rgba(0,0,0,0.3);
        transform: scale(1.05);
    }

    /* Warna dengan gradient */
    .bg-hadir { background: linear-gradient(135deg, #28a745, #4cd964); }
    .bg-sakit { background: linear-gradient(135deg, #dc3545, #ff6b6b); }
    .bg-izin { background: linear-gradient(135deg, #ffc107, #ffdd57); color: black; }
    .status-option input { display: none; }
</style>

<div class="container py-4">
    <div class="card shadow-lg border-0">
        @php
            use Carbon\Carbon;
            $formattedDate = Carbon::now()->translatedFormat('l, d F Y');
        @endphp

        <div class="card-header fw-bold text-white d-flex align-items-center justify-content-between"
            style="
                background: linear-gradient(135deg, #28a745, #34d058);
                font-size: 1.1rem;
                padding: 15px 20px;
                border-top-left-radius: 14px;
                border-top-right-radius: 14px;
                box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            ">
            <div class="d-flex align-items-center">
                <span style="font-size: 1.5rem; margin-right: 10px;">📅</span>
                <span>Absensi Hari Ini</span>
            </div>
            <div style="font-size: 1rem; font-weight: normal;">
                {{ $formattedDate }}
            </div>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
                $today = \Carbon\Carbon::today();
                $periodeMulai   = (isset($pengajuan) && $pengajuan->mulai_tanggal) ? \Carbon\Carbon::parse($pengajuan->mulai_tanggal) : null;
                $periodeSelesai = isset($pengajuan) ? \Carbon\Carbon::parse($pengajuan->sampai_tanggal) : null;
            @endphp

            @if($today->lt($periodeMulai))
                <div class="alert alert-danger text-center fw-bold">
                    ⏳ Periode magang belum dimulai, kamu belum bisa absen.
                </div>
            @elseif($today->gt($periodeSelesai))
                <div class="alert alert-danger text-center fw-bold">
                    ⛔ Periode magang sudah habis.
                </div>
            @elseif($alreadyAbsent)
                <div class="alert alert-info text-center fw-bold">
                    ✅ Kamu sudah absen hari ini.
                </div>
            @elseif($today->isWeekend())
                <div class="alert alert-warning text-center fw-bold">
                    🚫 Hari ini libur (Sabtu/Minggu), absensi tidak tersedia.
                </div>
            @else
                {{-- Form Absensi --}}
                <form action="{{ route('mahasiswa.absensi.store') }}" method="POST">
                    @csrf

                    <label class="form-label fw-bold mb-3">Pilih Status Kehadiran</label>
                    <div class="row g-3 mb-4 justify-content-center">
                        <div class="col-6 col-md-3">
                            <label class="status-option bg-hadir w-100">
                                <input type="radio" name="status" value="hadir" required>
                                ✅ Hadir
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="status-option bg-sakit w-100">
                                <input type="radio" name="status" value="sakit">
                                🤒 Sakit
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="status-option bg-izin w-100">
                                <input type="radio" name="status" value="izin">
                                📝 Izin
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="form-control" placeholder="Isi jika Izin/Sakit"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4">
                            💾 Simpan Absensi
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Form Filter Tanggal --}}
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('mahasiswa.absensi') }}" class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="tanggal" class="fw-bold text-success mb-0">📅 Filter Tanggal</label>
            </div>
            <div class="col-auto">
                <input type="date" id="tanggal" name="tanggal" 
                       value="{{ $filterTanggal ?? '' }}" 
                       class="form-control border-success shadow-sm">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn text-white shadow-sm" 
                        style="background: linear-gradient(135deg, #28a745, #34d058);">
                    🔍 Cari
                </button>
            </div>
            @if(!empty($filterTanggal))
                <div class="col-auto">
                    <a href="{{ route('mahasiswa.absensi') }}" 
                       class="btn text-white shadow-sm" 
                       style="background: linear-gradient(135deg, #6c757d, #868e96);">
                        ♻ Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Tabel Riwayat Absensi --}}
<div class="card mt-3 mb-4 shadow-sm border-0">
    <div class="card-header text-white fw-bold"
        style="background: linear-gradient(135deg, #28a745, #34d058);">
        📜 Riwayat Absensi Kamu
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 table-bordered border-secondary-subtle">
            <thead class="text-white" style="background: linear-gradient(135deg, #343a40, #495057);">
                <tr class="text-center">
                    <th style="width: 25%;">Tanggal</th>
                    <th style="width: 25%;">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatAbsensi as $absen)
                    <tr>
                        <td class="text-center fw-semibold">
                            {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="text-center">
                            @if($absen->status == 'hadir')
                                <span class="badge rounded-pill px-3 py-2 text-white" 
                                    style="background: linear-gradient(135deg, #28a745, #4cd964); font-size: 0.9rem;">
                                    ✅ Hadir
                                </span>
                            @elseif($absen->status == 'sakit')
                                <span class="badge rounded-pill px-3 py-2 text-white" 
                                    style="background: linear-gradient(135deg, #dc3545, #ff6b6b); font-size: 0.9rem;">
                                    🤒 Sakit
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 text-dark" 
                                    style="background: linear-gradient(135deg, #ffc107, #ffdd57); font-size: 0.9rem;">
                                    📝 Izin
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($absen->status == 'hadir' && empty($absen->keterangan))
                                Hadir melaksanakan magang
                            @else
                                {{ $absen->keterangan ?? '-' }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            Belum ada data absensi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- Script agar hanya satu checkbox bisa dipilih & efek selected --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="status"]');
    const labels = document.querySelectorAll('.status-option');

    radios.forEach(rb => {
        rb.addEventListener('change', function() {
            labels.forEach(lbl => lbl.classList.remove('selected'));
            if (this.checked) {
                this.closest('label').classList.add('selected');
            }
        });
    });
});
</script>
@endsection
