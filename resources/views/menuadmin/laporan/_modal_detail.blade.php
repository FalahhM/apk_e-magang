<style>
  /* Header Modal */
  .modal-header.bg-primary {
    background-color: #198754 !important; /* Hijau PTPN */
    color: #fff;
    border-bottom: 2px solid #146c43;
  }

  /* Judul Modal */
  .modal-title {
    font-weight: 600;
    font-size: 1.2rem;
  }

  /* Body Modal */
  .modal-body {
    background-color: #f6fff9; /* Hijau lembut */
    color: #333;
    font-size: 0.95rem;
  }

  /* List Group */
  .list-group-item {
    background-color: #fff;
    border: 1px solid #d1e7dd;
    border-left: 4px solid #198754;
    margin-bottom: 4px;
    padding: 8px 12px;
  }

  /* Footer Modal */
  .modal-footer {
    background-color: #e9f7ef;
    border-top: 1px solid #dee2e6;
  }

  /* Tombol */
  .btn-primary,
  .btn-success,
  .btn-warning {
    border-radius: 6px;
    font-weight: 500;
  }

  .btn-primary {
    background-color: #198754;
    border-color: #198754;
  }

  .btn-primary:hover {
    background-color: #146c43;
    border-color: #146c43;
  }

  .btn-success {
    background-color: #20c997;
    border-color: #20c997;
  }

  .btn-success:hover {
    background-color: #17a17a;
    border-color: #17a17a;
  }

  .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
  }

  .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
  }

  /* Spinner */
  .spinner-border {
    margin-left: 8px;
  }

  /* Responsive Scroll Area */
  .modal-body {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 10px;
  }

  /* Label Form */
  label {
    font-weight: 500;
    color: #333;
  }

  /* Input */
  .form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
  }
</style>



<div class="modal fade" id="detailModal{{ $pengajuan->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $pengajuan->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="detailModalLabel{{ $pengajuan->id }}">Detail Laporan - {{ $mahasiswa->nama_mahasiswa }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
        <p><strong>Email:</strong> {{ $mahasiswa->email }}</p>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Universitas:</strong> {{ $mahasiswa->user->name ?? '-' }}</p>
        <p><strong>Jumlah Hadir:</strong> {{ $mahasiswa->absensis->where('status', 'hadir')->count() }} hari</p>

        <hr>
        <h6>Riwayat Kirim Sertifikat:</h6>
        <ul class="list-group mb-3">
          <li class="list-group-item">
            Total Kirim : <strong>{{ $pengajuan->laporanMagang->jumlah_kirim }}</strong> kali
          </li>
          <li class="list-group-item">
            Terakhir Dikirim:
            <strong>
              @if($pengajuan->laporanMagang->terakhir_kirim_at)
                {{ $pengajuan->laporanMagang->terakhir_kirim_at->format('d M Y H:i') }}
              @else
                Belum dikirim
              @endif
            </strong>
          </li>
        </ul>
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
        @if($pengajuan->laporanMagang && $pengajuan->laporanMagang->jumlah_edit < 2)
          <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $pengajuan->id }}">
              Edit Nilai
          </button>
          @else
          <div class="alert alert-warning mb-2">
            <i class="bi bi-exclamation-triangle"></i>Nilai hanya dapat diedit maksimal sebanyak 2 kali
          </div>
        @endif
        @if($pengajuan->laporanMagang)
          <form id="form-kirim-sertifikat-{{ $pengajuan->id }}" action="{{ route('laporan.kirim', $pengajuan->laporanMagang->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="button" class="btn btn-success btn-kirim-sertifikat" data-id="{{ $pengajuan->id }}">Kirim Sertifikat
                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
              </button>
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

<script>
  let sedangMengirim = false;

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-kirim-sertifikat').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();

        if (sedangMengirim) return; // cegah klik ganda
        sedangMengirim = true;

        const id = this.dataset.id;
        const form = document.querySelector(`#form-kirim-sertifikat-${id}`);
        const url = form.getAttribute('action');

        const spinner = this.querySelector('.spinner-border');
        const btn = this;

        spinner.classList.remove('d-none');
        btn.disabled = true;

        Swal.showLoading();

        fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
            'Accept': 'application/json'
          }
        })
        .then(response => {
          if (!response.ok) throw new Error('Gagal mengirim sertifikat.');
          return response.json();
        })
        .then(data => {
          return Swal.fire({
            icon: 'success',
            title: '🎉 Sukses!',
            text: data.message || 'Sertifikat berhasil dikirim ke email mahasiswa.',
            confirmButtonColor: '#28a745',
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: true
          });
        })
        .then(() => {
          location.reload(); // reload setelah sukses
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: error.message || 'Terjadi kesalahan saat mengirim sertifikat.',
            confirmButtonColor: '#dc3545'
          });
        })
        .finally(() => {
          sedangMengirim = false;
          spinner.classList.add('d-none');
          btn.disabled = false;
        });
      }, { once: true }); // event listener hanya sekali
    });
  });
</script>
