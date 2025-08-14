@extends('templatedospem.header')
@section('title', 'Profil Dospem')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold" style="color:#1bbf6c">Profil Dosen Pembimbing</h3>
    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $dospem->nama_dospem ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $dospem->email ?? '-' }}</p>
            <p><strong>Kampus:</strong> {{ $kampusName }}</p>
            <p><strong>Mahasiswa Bimbingan:</strong>
                @if($dospem->mahasiswas->count())
                    <ul>
                        @foreach($dospem->mahasiswas as $mhs)
                            <li>{{ $mhs->nama_mahasiswa }} ({{ $mhs->nim }})</li>
                        @endforeach
                    </ul>
                @else
                    -
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
