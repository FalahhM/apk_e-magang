@extends('templatedospem.header')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 fw-bold text-primary text-center">📅 Data Absensi - {{ $mahasiswa->nama_mahasiswa }}</h1>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th width="20%">Tanggal</th>
                    <th width="20%">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $badgeClass = match($item->status) {
                                    'hadir' => 'success',
                                    'izin' => 'warning',
                                    'sakit' => 'info',
                                    'alfa' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} text-uppercase px-3 py-2 text-white">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Belum ada data absensi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
