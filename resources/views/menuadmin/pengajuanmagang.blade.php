@extends('templateadmin.header')
@section('content')

<div class="container py-4">
    <h3 class="mb-4 fw-bold text-success">
        <i class="bi bi-file-earmark-text me-2"></i>Pengajuan Magang
    </h3>

    {{-- Filter Form --}}
    <div class="card border-success shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="no_surat" class="form-control" placeholder="Cari No. Surat" value="{{ request('no_surat') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="nama_kampus" class="form-control" placeholder="Cari Nama Kampus" value="{{ request('nama_kampus') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('tampilPengajuan') }}" style="margin-left: 12px" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border-success shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                @if($data_pengajuan->isNotEmpty())
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>No. Surat</th>
                            <th>Nama Kampus</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_pengajuan as $key => $pengajuan)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $pengajuan->no_surat }}</td>
                            <td>{{ $pengajuan->user->name }}</td>
                            <td>{{ $pengajuan->tanggal_surat }}</td>
                            <td>{{ $pengajuan->perihal }}</td>
                            <td>
                                @php
                                    $badgeColor = match($pengajuan->status) {
                                        'Diterima' => 'success',
                                        'Ditolak' => 'danger',
                                        'Sedang Di Proses' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">{{ $pengajuan->status }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('detailpengajuan', ['id' => $pengajuan->id]) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="alert alert-warning text-center m-0">Data pengajuan tidak tersedia.</div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
