<div class="modal fade" id="detailModal{{ $pengajuan->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $pengajuan->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="detailModalLabel{{ $pengajuan->id }}">Detail Laporan - {{ $mahasiswa->nama }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
        <p><strong>Email:</strong> {{ $mahasiswa->email }}</p>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Universitas:</strong> {{ $mahasiswa->user->name ?? '-' }}</p>
        <p><strong>Jumlah Hadir:</strong> {{ $mahasiswa->absensis->where('status', 'hadir')->count() }} hari</p>

        <hr>
        <h6>Nilai Laporan Magang:</h6>
        <ul class="list-group">
          <li class="list-group-item">Integritas: <strong>{{ $pengajuan->laporanMagang->integritas }}</strong></li>
          <li class="list-group-item">Ketepatan Waktu: <strong>{{ $pengajuan->laporanMagang->ketepatan_waktu }}</strong></li>
          <li class="list-group-item">Keahlian: <strong>{{ $pengajuan->laporanMagang->keahlian }}</strong></li>
          <li class="list-group-item">Team Work: <strong>{{ $pengajuan->laporanMagang->teamwork }}</strong></li>
          <li class="list-group-item">Komunikasi: <strong>{{ $pengajuan->laporanMagang->komunikasi }}</strong></li>
          <li class="list-group-item">Penggunaan Teknologi Informasi: <strong>{{ $pengajuan->laporanMagang->teknologi }}</strong></li>
          <li class="list-group-item">Pengembangan Diri: <strong>{{ $pengajuan->laporanMagang->pengembangan_diri }}</strong></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $pengajuan->id }}">
            Edit Nilai
        </button>
        @if($pengajuan->laporanMagang)
          <form action="{{ route('laporan.kirim', $pengajuan->laporanMagang->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-success">Kirim Sertifikat</button>
          </form>
        @endif
        <a href="{{ route('laporan.lihat', $laporan->id) }}" class="btn btn-sm btn-primary" target="_blank">
            <i class="bi bi-eye"></i> Lihat Sertifikat
        </a>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal{{ $pengajuan->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pengajuan->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editModalLabel{{ $pengajuan->id }}">Edit Nilai - {{ $mahasiswa->nama_mahasiswa }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('laporan.update', $pengajuan->laporanMagang->id) }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Integritas</label>
                    <input type="number" name="integritas" class="form-control" value="{{ $pengajuan->laporanMagang->integritas }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Ketepatan Waktu</label>
                    <input type="number" name="ketepatan_waktu" class="form-control" value="{{ $pengajuan->laporanMagang->ketepatan_waktu }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Keahlian</label>
                    <input type="number" name="keahlian" class="form-control" value="{{ $pengajuan->laporanMagang->keahlian }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Teamwork</label>
                    <input type="number" name="teamwork" class="form-control" value="{{ $pengajuan->laporanMagang->teamwork }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Komunikasi</label>
                    <input type="number" name="komunikasi" class="form-control" value="{{ $pengajuan->laporanMagang->komunikasi }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Penggunaan Teknologi Informasi</label>
                    <input type="number" name="teknologi" class="form-control" value="{{ $pengajuan->laporanMagang->teknologi }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Pengembangan Diri</label>
                    <input type="number" name="pengembangan_diri" class="form-control" value="{{ $pengajuan->laporanMagang->pengembangan_diri }}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

