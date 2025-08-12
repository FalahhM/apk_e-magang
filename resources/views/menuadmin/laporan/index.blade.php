    @extends('templateadmin.header')
    @section('title', 'Laporan Magang')

    @section('content')
    <div class="container py-4">
        <h3 class="mb-4 fw-bold text-primary">
            <i class="bi bi-clipboard-data"></i> Laporan Magang Mahasiswa
        </h3>

        <div class="table-responsive shadow rounded">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Universitas</th>
                        <th>Jumlah Hadir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($pengajuanSelesai as $pengajuan)
                    @php
                        $mahasiswa = $pengajuan->mahasiswas->first();
                    @endphp
                    <tr>
                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->user->name ?? '-' }}</td>
                        <td>{{ $mahasiswa->absensis->where('status', 'hadir')->count() }} hari</td>
                        <td>
                            @php
                                $laporan = $pengajuan->laporanMagang;
                            @endphp

                            @if($laporan)
                                <!-- Tombol detail -->
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pengajuan->id }}">
                                    Detail
                                </button>

                                @include('menuadmin.laporan._modal_detail', ['pengajuan' => $pengajuan, 'mahasiswa' => $mahasiswa])
                            @else
                                <!-- Tombol isi nilai -->
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#isiModal{{ $pengajuan->id }}">
                                    Isi Nilai
                                </button>   

                                @include('menuadmin.laporan._modal_isi_nilai', ['pengajuan' => $pengajuan, 'mahasiswa' => $mahasiswa])
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
    @endsection
