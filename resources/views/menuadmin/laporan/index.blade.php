@extends('templateadmin.header')
@section('title', 'Laporan Magang')

@section('content')
<div class="container py-4">
    <div class="ptpn-card mb-4">
        <div class="ptpn-header mb-4">
            <h3 class="mb-0">
                <i class="bi bi-clipboard-data"></i> Laporan Magang Mahasiswa
            </h3>
            <p class="mb-0" style="color: #e6fff5;">
                Daftar mahasiswa yang telah menyelesaikan magang beserta status pengiriman sertifikat.
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="nama" class="form-control" placeholder="Cari berdasarkan nama"
                    value="{{ request('nama') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="nim" class="form-control" placeholder="Cari berdasarkan NIM"
                    value="{{ request('nim') }}">
            </div>
            <div class="col-md-4">
                <select name="universitas" id="universitas" class="form-control">
                    <option value="">-- Semua Universitas --</option>
                    @foreach ($kampusList as $kampus)
                        <option value="{{ $kampus }}" {{ request('universitas') == $kampus ? 'selected' : '' }}>
                            {{ $kampus }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 d-flex justify-content-end gap-3 mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary" style="margin-left: 12px">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="table-responsive ptpn-card">
        <table class="table table-hover table-bordered ptpn-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Universitas</th>
                    <th>Jumlah Hadir</th>
                    <th>Aksi</th>
                    <th>Jumlah Kirim Sertifikat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuanSelesai as $mahasiswa)
                    @php
                        $penilaian = $mahasiswa->penilaianMagang ?? null;
                    @endphp
                    <tr>
                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->pengajuan->user->name ?? '-' }}</td>
                        <td>{{ $mahasiswa->absensis->where('status', 'hadir')->count() }} hari</td>
                        <td>
                            @if($penilaian)
                                {{-- Jika sudah ada penilaian, tampilkan Detail --}}
                                <button class="ptpn-btn-detail" data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $mahasiswa->id }}">
                                    Detail
                                </button>
                                @include('menuadmin.laporan._modal_detail', [
                                    'mahasiswa' => $mahasiswa,
                                    'penilaian' => $penilaian
                                ])
                            @else
                                {{-- Jika belum ada, tampilkan form isi nilai --}}
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#isiModal{{ $mahasiswa->id }}">
                                    Isi Nilai
                                </button>
                                @include('menuadmin.laporan._modal_isi_nilai', [
                                    'mahasiswa' => $mahasiswa
                                ])
                            @endif
                        </td>

                        <td>
                            @if($penilaian && $penilaian->jumlah_kirim > 0)
                                <span class="ptpn-badge-success">
                                    <i class="bi bi-envelope-check-fill me-1"></i>
                                    {{ $penilaian->jumlah_kirim }} kali
                                </span>
                            @else
                                <span class="ptpn-badge-fail">
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                    Belum kirim sertifikat
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Tidak ada data mahasiswa</h5>
                                <p class="text-muted">
                                    Belum ada mahasiswa yang menyelesaikan magang atau sesuai dengan filter yang dipilih.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
