@extends('templatedospem.header')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary fw-bold">📚 Daftar Mahasiswa Bimbingan</h1>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('dospem.absensi') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari mahasiswa atau NIM...">
            <button class="btn btn-primary ml-3" type="submit">🔍 Cari</button>
        </div>
    </form>

    <div class="row">
        @forelse($mahasiswaList as $mhs)
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-3 custom-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold mb-1">{{ $mhs->nama_mahasiswa }}</h5>
                            <p class="text-muted mb-0">NIM: {{ $mhs->nim }}</p>
                        </div>
                        <a href="{{ route('dospem.absensi.detail', $mhs->id) }}" 
                           class="btn btn-primary btn-sm">
                            📄 Lihat Detail Absensi
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada mahasiswa bimbingan.
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Custom Styling --}}
<style>
    .custom-card {
        border-left: 5px solid #0d6efd; /* Aksen biru di kiri */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.1rem;
    }
</style>
@endsection
