@extends('template.header')
@section('content')

<style>
    body {
        background-color: #eef7ee;
    }

    .card-header {
        background-color: #388e3c;
        color: white;
        font-weight: 600;
    }

    .btn-info {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }

    .btn-info:hover {
        background-color: #256428;
        border-color: #256428;
    }

    h2 {
        color: #2e7d32;
        font-weight: bold;
    }

    #notifFloating {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #2e7d32;
        color: white;
        padding: 12px 24px;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        cursor: pointer;
        opacity: 1;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    #notifFloating.hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    #notifFloating:hover {
        background-color: #256428;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4 text-center">Dashboard Kampus - PTPN IV Regional IV</h2>

    {{-- Data Kampus --}}
    <div class="card mb-4 shadow">
        <div class="card-header">📘 Data Kampus</div>
        <div class="card-body">
            <p><strong>Nama Kampus:</strong> {{ $user->name }}</p>
            <p><strong>No. Telepon:</strong> {{ $user->no_telp }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Alamat Kampus:</strong> {{ $user->alamat }}</p>
        </div>
    </div>

    {{-- Data Contact Person --}}
    <div class="card mb-4 shadow">
        <div class="card-header">👤 Contact Person</div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $contact_person->namecp }}</p>
            <p><strong>Email:</strong> {{ $contact_person->emailcp }}</p>
            <p><strong>No. HP:</strong> {{ $contact_person->nohpcp }}</p>
            <p><strong>Jabatan:</strong> {{ $contact_person->jabatan }}</p>
        </div>
    </div>

    {{-- Data Pengajuan Magang --}}
    <div class="card shadow" id="pengajuan">
        <div class="card-header">📄 Daftar Pengajuan Magang</div>
        <div class="card-body">
            @if($pengajuan->count())
                <table class="table table-bordered table-striped">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>No. Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan as $peng)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $peng->no_surat }}</td>
                                <td>{{ \Carbon\Carbon::parse($peng->tanggal_surat)->format('d-m-Y') }}</td>
                                <td>{{ $peng->perihal }}</td>
                                <td>
                                    <span class="badge bg-{{ $peng->status == 'Diterima' ? 'success' : ($peng->status == 'Ditolak' ? 'danger' : 'secondary') }}">
                                        {{ $peng->status }}
                                    </span>
                                </td>
                                <td>
                                    <button 
                                        type="button" 
                                        class="btn btn-info btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#detailModal{{ $peng->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Belum ada pengajuan magang.</p>
            @endif
        </div>
    </div>
</div>

{{-- Floating Notification --}}
@if($pengajuan->count())
    <div id="notifFloating" onclick="scrollToPengajuan()">
        📄 Lihat Daftar Pengajuan Magang
    </div>
@endif

{{-- Modal Detail Pengajuan --}}
@foreach($pengajuan as $peng)
<div class="modal fade" id="detailModal{{ $peng->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $peng->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalLabel{{ $peng->id }}">Detail Pengajuan Magang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>No Surat:</strong> {{ $peng->no_surat }}</p>
                <p><strong>Tanggal Surat:</strong> {{ \Carbon\Carbon::parse($peng->tanggal_surat)->format('d-m-Y') }}</p>
                <p><strong>Perihal:</strong> {{ $peng->perihal }}</p>
                <p><strong>Periode Magang:</strong> {{ $peng->mulai_tanggal }} s.d {{ $peng->sampai_tanggal }}</p>
                <p><strong>Status:</strong> {{ $peng->status }}</p>

                <hr>
                <h6>Data Mahasiswa</h6>
                @if($peng->mahasiswas && $peng->mahasiswas->count())
                    <table class="table table-sm table-bordered">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Dosen Pembimbing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peng->mahasiswas as $mhs)
                                <tr>
                                    <td>{{ $mhs->nama_mahasiswa }}</td>
                                    <td>{{ $mhs->email }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->jurusan }}</td>
                                    <td>{{ $mhs->dospem }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Tidak ada data mahasiswa.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    function scrollToPengajuan() {
        const target = document.getElementById('pengajuan');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const notif = document.getElementById('notifFloating');

        @if($pengajuan->count())
            notif.style.display = 'block';
        @endif

        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                notif.classList.add('hide');
            } else {
                notif.classList.remove('hide');
            }
        });
    });
</script>

@endsection
