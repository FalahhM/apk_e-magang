@extends('templateadmin.header')
@section('content')

<div class="container">
    <h3>Detail Pengajuan Magang</h3>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 d-flex justify-content-between">
                    @if($pengajuan->status === 'Sedang Di Proses')
                        <a href="{{ route('cetakProsesPDF', ['id' => $pengajuan->id]) }}" class="btn btn-primary">Cetak</a>
                        <form action="{{ route('terimaPengajuan', ['id' => $pengajuan->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Terima</button>
                        </form>
                        <!-- Tombol Tolak -->
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Tolak</button>
                    @elseif($pengajuan->status === 'Pending')
                        <form action="{{ route('prosesPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Sedang Di Proses"> 
                            <button type="submit" class="btn btn-primary">Proses</button>
                        </form>
                    @elseif($pengajuan->status === 'Diterima')
                        <a href="{{ route('lihatSuratTerima', ['id' => $pengajuan->id]) }}" class="btn btn-primary">Lihat Surat Terima</a>
                    @elseif($pengajuan->status === 'Ditolak')
                        <a href="{{ route('lihatSuratTolak', ['id' => $pengajuan->id]) }}" class="btn btn-primary">Lihat Surat Tolak</a>    
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>No. Surat:</h5>
                    <p>{{ $pengajuan->no_surat }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Tanggal Pengajuan:</h5>
                    <p>({{ \Carbon\Carbon::parse($pengajuan->tanggal_surat)->format('d/m/Y') }})</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Perihal:</h5>
                    <p>{{ $pengajuan->perihal }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Periode:</h5>
                    <p>({{ \Carbon\Carbon::parse($pengajuan->mulai_tanggal)->format('d/m/Y') }}) - ({{ \Carbon\Carbon::parse($pengajuan->sampai_tanggal)->format('d/m/Y') }})</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Surat:</h5>
                    @if($pengajuan->dokumen_file)
                        <a href="{{ \Storage::url($pengajuan->dokumen_file) }}" target="_blank" class="btn btn-primary">Lihat Surat {{$pengajuan->perihal}}</a>
                    @else
                        <p>Dokumen tidak tersedia</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Data Kampus</h5>
                    <p><strong>Nama Kampus:</strong> {{ $pengajuan->user->name }}</p>
                    <p><strong>No. Telp:</strong> {{ $pengajuan->user->no_telp }}</p>
                    <p><strong>Email:</strong> {{ $pengajuan->user->email }}</p>
                    <p><strong>Alamat Kampus:</strong> {{ $pengajuan->user->alamat }}</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Contact Person</h5>
                    <p><strong>Nama:</strong> {{ $pengajuan->user->contactPerson->namecp }}</p>
                    <p><strong>Email:</strong> {{ $pengajuan->user->contactPerson->emailcp }}</p>
                    <p><strong>No. HP:</strong> {{ $pengajuan->user->contactPerson->nohpcp }}</p>
                    <p><strong>Jabatan:</strong> {{ $pengajuan->user->contactPerson->jabatan }}</p>
                </div>
            </div>

            <!-- Tabel Data Mahasiswa -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Data Mahasiswa</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Dosen Pembimbing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuan->mahasiswas as $index => $mahasiswa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                                    <td>{{ $mahasiswa->nim }}</td>
                                    <td>{{ $mahasiswa->jurusan }}</td>
                                    <td>{{ $mahasiswa->dospem }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('tolakPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Masukkan Alasan Penolakan</label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
