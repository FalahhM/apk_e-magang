@extends('templateadmin.header')
@section('content')

<div class="container py-4">
    <h3 class="mb-4 fw-bold text-success">
        <i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan Magang
    </h3>

    <div class="card border-success shadow-sm mb-4">
        <div class="card-body">
            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-start gap-2 mb-4">
                @if($pengajuan->status === 'Sedang Di Proses')
                    <a href="{{ route('cetakProsesPDF', ['id' => $pengajuan->id]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-printer"></i> Cetak Surat Proses
                    </a>
                    <form action="{{ route('terimaPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Terima
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Tolak
                    </button>
                @elseif($pengajuan->status === 'Pending')
                    <form action="{{ route('prosesPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="Sedang Di Proses">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-hourglass-split"></i> Proses
                        </button>
                    </form>
                @elseif($pengajuan->status === 'Diterima')
                    <a href="{{ route('lihatSuratTerima', ['id' => $pengajuan->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-check"></i> Lihat Surat Terima
                    </a>
                @elseif($pengajuan->status === 'Ditolak')
                    <a href="{{ route('lihatSuratTolak', ['id' => $pengajuan->id]) }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-earmark-excel"></i> Lihat Surat Tolak
                    </a>
                @endif
            </div>

            {{-- Informasi Pengajuan --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">No. Surat</h6>
                    <p class="fw-semibold">{{ $pengajuan->no_surat }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Tanggal Pengajuan</h6>
                    <p>{{ \Carbon\Carbon::parse($pengajuan->tanggal_surat)->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Perihal</h6>
                    <p>{{ $pengajuan->perihal }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Periode Magang</h6>
                    <p>{{ \Carbon\Carbon::parse($pengajuan->mulai_tanggal)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($pengajuan->sampai_tanggal)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="mb-4">
                <h6 class="text-muted">Dokumen</h6>
                @if($pengajuan->dokumen_file)
                    <a href="{{ \Storage::url($pengajuan->dokumen_file) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-text"></i> Lihat Surat {{ $pengajuan->perihal }}
                    </a>
                @else
                    <p class="text-danger">Dokumen tidak tersedia.</p>
                @endif
            </div>

            {{-- Data Kampus --}}
            <div class="mb-4">
                <h5 class="text-success">Data Kampus</h5>
                <p><strong>Nama Kampus:</strong> {{ $pengajuan->user->name }}</p>
                <p><strong>No. Telp:</strong> {{ $pengajuan->user->no_telp }}</p>
                <p><strong>Email:</strong> {{ $pengajuan->user->email }}</p>
                <p><strong>Alamat Kampus:</strong> {{ $pengajuan->user->alamat }}</p>
            </div>

            {{-- Contact Person --}}
            <div class="mb-4">
                <h5 class="text-success">Contact Person</h5>
                <p><strong>Nama:</strong> {{ $pengajuan->user->contactPerson->namecp }}</p>
                <p><strong>Email:</strong> {{ $pengajuan->user->contactPerson->emailcp }}</p>
                <p><strong>No. HP:</strong> {{ $pengajuan->user->contactPerson->nohpcp }}</p>
                <p><strong>Jabatan:</strong> {{ $pengajuan->user->contactPerson->jabatan }}</p>
            </div>

            {{-- Data Mahasiswa --}}
            <div>
                <h5 class="text-success">Data Mahasiswa</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Email</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Dosen Pembimbing</th>
                            <th>Email Dosen Pembimbing</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan->mahasiswas as $index => $mahasiswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                            <td>{{ $mahasiswa->email }}</td>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->jurusan }}</td>
                            <td>{{ $mahasiswa->dospem->nama_dospem ?? '-' }}</td>
                            <td>{{ $mahasiswa->dospem->email ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tolakPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="alasan" class="form-label">Masukkan Alasan Penolakan</label>
                    <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
