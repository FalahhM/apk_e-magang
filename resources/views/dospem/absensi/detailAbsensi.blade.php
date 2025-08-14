@extends('templatedospem.header')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 fw-bold text-primary text-center">📅 Data Absensi - {{ $mahasiswa->nama_mahasiswa }} ({{ $mahasiswa->nim }})</h1>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('dospem.absensi.detail', $mahasiswa->id) }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>
            <div class="col-md-4 d-flex">
                <button type="submit" class="btn btn-primary mr-3 me-2">Filter</button>
                <a href="{{ route('dospem.absensi.detail', $mahasiswa->id) }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

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
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal)
                                  ->locale('id')
                                  ->translatedFormat('d F Y') }}
                        </td>
                        <td class="text-capitalize">{{ $item->status }}</td>
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
