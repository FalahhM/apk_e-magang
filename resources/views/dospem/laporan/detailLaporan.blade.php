@extends('templatedospem.header')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0">📄 Laporan Kegiatan: {{ $mahasiswa->nama_mahasiswa }} ({{ $mahasiswa->nim }})</h2>
    </div>

    {{-- Filter Tanggal --}}
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label for="tanggal" class="form-label">Cari Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal"
                   value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-success mt-auto">🔍 Filter</button>
            <a href="{{ route('dospem.laporan.detail', $mahasiswa->id) }}" class="btn btn-secondary ml-3 mt-auto">♻ Reset</a>
        </div>
    </form>

    @if($laporanList->isEmpty())
        <div class="alert alert-warning text-center">⚠ Belum ada laporan yang diunggah pada tanggal tersebut.</div>
    @else
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-success">
                    <tr>
                        <th style="width: 20%">Tanggal</th>
                        <th style="width: 50%">Keterangan</th>
                        <th style="width: 15%">Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanList as $laporan)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_kegiatan)
                                  ->locale('id')
                                  ->translatedFormat('d F Y') }}
                            </td>
                            <td class="text-wrap">{{ $laporan->keterangan }}</td>
                            <td>
                                @if($laporan->foto_dokumentasi)
                                    <a href="{{ asset('storage/'.$laporan->foto_dokumentasi) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary">
                                       📷 Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
