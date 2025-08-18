@extends('templatemahasiswa.header')

@section('title', 'Laporan Kegiatan Magang')

@section('content')
<style>
    /* ===== Palet Warna Hijau PTPN Selaras Navbar & Sidebar ===== */
    :root {
        --ptpn-green: #0B8A28; /* Warna utama */
        --ptpn-green-dark: #086A20; /* Versi gelap */
        --ptpn-green-light: #0fae34; /* Versi terang */
    }

    /* Background hijau */
    .bg-ptpn {
        background-color: var(--ptpn-green) !important;
    }

    /* Tombol utama */
    .btn-ptpn {
        background-color: var(--ptpn-green) !important;
        border: none;
        color: white !important;
    }
    .btn-ptpn:hover {
        background-color: var(--ptpn-green-dark) !important;
        color: white !important;
    }

    /* Tombol outline */
    .btn-outline-ptpn {
        border: 1px solid var(--ptpn-green) !important;
        color: var(--ptpn-green) !important;
        background-color: transparent !important;
    }
    .btn-outline-ptpn:hover {
        background-color: var(--ptpn-green) !important;
        color: white !important;
    }

    /* Tabel */
    .table-ptpn thead {
        background-color: var(--ptpn-green) !important;
        color: white !important;
    }
    .table-ptpn th,
    .table-ptpn td {
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* Tombol Edit & Hapus sejajar */
    .btn-group-action {
        display: flex;
        gap: 5px;
        justify-content: center;
        flex-wrap: nowrap;
        align-items: center;
    }
    .btn-group-action form {
        display: inline-flex;
        margin: 0;
    }
</style>

<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-ptpn text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i> Laporan Kegiatan Magang</h4>
        </div>
        <div class="card-body">

            {{-- Form Input Nama Pembimbing --}}
            <form action="{{ route('mahasiswa.laporan.simpanPembimbing') }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label for="nama_pembimbing_lapangan" class="form-label fw-semibold">
                            <i class="bi bi-person-badge me-1"></i> Nama Pembimbing Lapangan
                        </label>
                        <input type="text" name="nama_pembimbing_lapangan" id="nama_pembimbing_lapangan"
                               value="{{ $laporans->first()->nama_pembimbing_lapangan ?? '' }}"
                               class="form-control border-success shadow-sm" placeholder="Masukkan nama pembimbing">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-ptpn w-100">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mb-3">
                @if($periodeHabis)
                    <button type="button" class="btn btn-ptpn shadow-sm" onclick="showNotifPeriode()">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
                    </button>
                @elseif($sudahAbsen)
                    <a href="{{ route('mahasiswa.laporan.create') }}" class="btn btn-ptpn shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
                    </a>
                @else
                    <button type="button" class="btn btn-ptpn shadow-sm" onclick="showNotifAbsen()">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
                    </button>
                @endif

                <a href="{{ route('mahasiswa.laporan.cetakSemua') }}" class="btn btn-warning shadow-sm text-white">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </a>
            </div>

            {{-- Pesan Sukses / Error --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger shadow-sm">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Tabel Laporan --}}
            <div class="table-responsive">
                <table class="table table-hover table-ptpn align-middle">
                    <thead>
                        <tr>
                            <th style="width: 15%">Tanggal</th>
                            <th style="width: 40%">Keterangan</th>
                            <th style="width: 20%">Foto Dokumentasi</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($laporans as $laporan)
                        <tr>
                            <td>{{ $laporan->tanggal_kegiatan }}</td>
                            <td>{{ $laporan->keterangan }}</td>
                            <td>
                                @if($laporan->foto_dokumentasi)
                                    <img src="{{ asset('storage/'.$laporan->foto_dokumentasi) }}"
                                         class="img-thumbnail rounded shadow-sm" width="80">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-action">
                                    <a href="{{ route('mahasiswa.laporan.edit', $laporan->id) }}"
                                       class="btn btn-sm btn-outline-ptpn">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('mahasiswa.laporan.destroy', $laporan->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin mau hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class="bi bi-emoji-frown"></i> Belum ada laporan
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showNotifAbsen(){
        Swal.fire({
            icon : 'warning',
            title : 'Isi Absen Terlebih Dahulu',
            text : 'Anda harus mengisi absen hari ini sebelum menambah laporan.',
            confirmButtonColor : '#0B8A28'
        })
    }

    function showNotifPeriode(){
        Swal.fire({
            icon : 'info',
            title : 'Periode Magang Selesai',
            text : 'Anda tidak dapat menambah laporan karena periode magang sudah selesai.',
            confirmButtonColor : '#0B8A28'
        })
    }
</script>
@endsection
