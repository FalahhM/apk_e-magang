<style>
  /* Header Modal */
  .modal-header.bg-primary {
    background-color: #198754 !important;
    color: #fff;
    border-bottom: 2px solid #146c43;
  }

  .modal-header.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
    border-bottom: 2px solid #d39e00;
  }

  .modal-title {
    font-weight: 600;
    font-size: 1.2rem;
  }

  .modal-body {
    background-color: #f6fff9;
    color: #333;
    font-size: 0.95rem;
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 10px;
  }

  .list-group-item {
    background-color: #fff;
    border: 1px solid #d1e7dd;
    border-left: 4px solid #198754;
    margin-bottom: 4px;
    padding: 8px 12px;
  }

  .modal-footer {
    background-color: #e9f7ef;
    border-top: 1px solid #dee2e6;
  }

  .btn-primary, .btn-success, .btn-warning {
    border-radius: 6px;
    font-weight: 500;
  }

  .btn-primary { background-color: #198754; border-color: #198754; }
  .btn-primary:hover { background-color: #146c43; border-color: #146c43; }

  .btn-success { background-color: #20c997; border-color: #20c997; }
  .btn-success:hover { background-color: #17a17a; border-color: #17a17a; }

  .btn-warning { background-color: #ffc107; border-color: #ffc107; color: #000; }
  .btn-warning:hover { background-color: #e0a800; border-color: #d39e00; }

  .spinner-border { margin-left: 8px; }

  label { font-weight: 500; color: #333; }
  .form-control { border-radius: 6px; border: 1px solid #ced4da; }
</style>

{{-- ================== MODAL DETAIL & EDIT/ISI NILAI ================== --}}
<div class="modal fade" id="detailModal{{ $mahasiswa->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $mahasiswa->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header {{ $mahasiswa->penilaianMagang ? 'bg-primary text-white' : 'bg-warning text-dark' }}">
        <h5 class="modal-title" id="detailModalLabel{{ $mahasiswa->id }}">
          {{ $mahasiswa->penilaianMagang ? 'Detail Laporan' : 'Isi Nilai' }} - {{ $mahasiswa->nama_mahasiswa }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
        <p><strong>Email:</strong> {{ $mahasiswa->email }}</p>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Universitas:</strong> {{ $mahasiswa->pengajuan->user->name ?? '-' }}</p>
        <p><strong>Jumlah Hadir:</strong> {{ $mahasiswa->absensis->where('status', 'hadir')->count() }} hari</p>
        <hr>
            <h6>Daftar Laporan Kegiatan:</h6>
            @if($mahasiswa->laporanGrouped->isNotEmpty())
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Foto Dokomentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswa->laporanGrouped as $tanggal => $laporans)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach($laporans as $laporan)
                                            <li>{{ $laporan->keterangan }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                  <ul class="mb-0">
                                      @foreach($laporans as $laporan)
                                          @if($laporan->foto_dokumentasi) {{-- pastikan ada field foto di tabel laporan --}}
                                              <li>
                                                  <img src="{{ asset('storage/' . $laporan->foto_dokumentasi) }}" 
                                                      alt="Dokumentasi" 
                                                      style="max-width: 100px; max-height: 80px;">
                                              </li>
                                          @else
                                              <li>-</li>
                                          @endif
                                      @endforeach
                                  </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>- Belum ada laporan kegiatan -</p>
            @endif
        @if($mahasiswa->penilaianMagang)
          <hr>
          <h6>Riwayat Kirim Sertifikat:</h6>
          <ul class="list-group mb-3">
            <li class="list-group-item">
              Total Kirim: <strong>{{ $mahasiswa->penilaianMagang->jumlah_kirim ?? 0 }}</strong> kali
            </li>
            <li class="list-group-item">
              Terakhir Dikirim:
              <strong>
                {{ $mahasiswa->penilaianMagang && $mahasiswa->penilaianMagang->terakhir_kirim_at
                    ? $mahasiswa->penilaianMagang->terakhir_kirim_at->format('d M Y H:i')
                    : 'Belum dikirim' }}
              </strong>
            </li>

          </ul>

          <h6>Nilai Laporan Magang:</h6>
          <ul class="list-group mb-3">
            <li class="list-group-item">Integritas: <strong>{{ $mahasiswa->penilaianMagang->integritas ?? '-' }}</strong></li>
            <li class="list-group-item">Ketepatan Waktu: <strong>{{ $mahasiswa->penilaianMagang->ketepatan_waktu ?? '-' }}</strong></li>
            <li class="list-group-item">Keahlian: <strong>{{ $mahasiswa->penilaianMagang->keahlian ?? '-' }}</strong></li>
            <li class="list-group-item">Teamwork: <strong>{{ $mahasiswa->penilaianMagang->teamwork ?? '-' }}</strong></li>
            <li class="list-group-item">Komunikasi: <strong>{{ $mahasiswa->penilaianMagang->komunikasi ?? '-' }}</strong></li>
            <li class="list-group-item">Teknologi: <strong>{{ $mahasiswa->penilaianMagang->teknologi ?? '-' }}</strong></li>
            <li class="list-group-item">Pengembangan Diri: <strong>{{ $mahasiswa->penilaianMagang->pengembangan_diri ?? '-' }}</strong></li>
            <li class="list-group-item">Total Nilai: <strong>{{ $mahasiswa->penilaianMagang->total_nilai ?? '-' }}</strong></li>
            <li class="list-group-item">Predikat: <strong>{{ $mahasiswa->penilaianMagang->predikat ?? '-' }}</strong></li>
          </ul>
        @endif
      </div>

      <div class="modal-footer d-flex flex-wrap gap-2">
        @if($mahasiswa->penilaianMagang)
          {{-- Tombol Kirim Sertifikat --}}
          <form id="form-kirim-sertifikat-{{ $mahasiswa->penilaianMagang->id }}" 
                action="{{ route('laporan.kirim', $mahasiswa->penilaianMagang->id) }}" 
                method="POST" class="d-inline">
              @csrf
              <button type="button" 
                      class="btn btn-success btn-kirim-sertifikat" 
                      data-id="{{ $mahasiswa->penilaianMagang->id }}">
                  Kirim Sertifikat
                  <span class="spinner-border spinner-border-sm d-none me-2" 
                        role="status" aria-hidden="true"></span>
              </button>
          </form>
          <a href="{{ route('laporan.lihat', $mahasiswa->penilaianMagang->id) }}" class="btn btn-sm btn-primary" target="_blank">
              <i class="bi bi-eye"></i> Lihat Sertifikat
          </a>
        @endif

        {{-- Tombol Edit / Isi Nilai --}}
        @if($mahasiswa->penilaianMagang)
            @if($mahasiswa->penilaianMagang->jumlah_edit < 2)
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $mahasiswa->id }}">
                  Edit Nilai
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-disabled-edit" data-id="{{ $mahasiswa->id }}">
                  Edit Nilai (Maks 2x)
                </button>
            @endif
        @else
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal{{ $mahasiswa->id }}">
              Isi Nilai
            </button>
        @endif

        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- ================== MODAL EDIT / ISI NILAI ================== --}}
<div class="modal fade" id="editModal{{ $mahasiswa->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $mahasiswa->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header {{ $mahasiswa->penilaianMagang ? 'bg-warning text-dark' : 'bg-success text-white' }}">
        <h5 class="modal-title" id="editModalLabel{{ $mahasiswa->id }}">
          {{ $mahasiswa->penilaianMagang ? 'Edit Nilai' : 'Isi Nilai' }} - {{ $mahasiswa->nama_mahasiswa }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <form action="{{ $mahasiswa->penilaianMagang ? route('laporan.update', $mahasiswa->penilaianMagang->id) : route('laporan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">

        <div class="modal-body">
          <div class="row">
              <div class="col-md-6 mb-3">
                  <label>Integritas</label>
                  <input type="number" name="integritas" class="form-control" value="{{ $mahasiswa->penilaianMagang->integritas ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Ketepatan Waktu</label>
                  <input type="number" name="ketepatan_waktu" class="form-control" value="{{ $mahasiswa->penilaianMagang->ketepatan_waktu ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Keahlian</label>
                  <input type="number" name="keahlian" class="form-control" value="{{ $mahasiswa->penilaianMagang->keahlian ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Teamwork</label>
                  <input type="number" name="teamwork" class="form-control" value="{{ $mahasiswa->penilaianMagang->teamwork ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Komunikasi</label>
                  <input type="number" name="komunikasi" class="form-control" value="{{ $mahasiswa->penilaianMagang->komunikasi ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Teknologi</label>
                  <input type="number" name="teknologi" class="form-control" value="{{ $mahasiswa->penilaianMagang->teknologi ?? '' }}">
              </div>
              <div class="col-md-6 mb-3">
                  <label>Pengembangan Diri</label>
                  <input type="number" name="pengembangan_diri" class="form-control" value="{{ $mahasiswa->penilaianMagang->pengembangan_diri ?? '' }}">
              </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn {{ $mahasiswa->penilaianMagang ? 'btn-warning' : 'btn-success' }}">
              {{ $mahasiswa->penilaianMagang ? 'Simpan Perubahan' : 'Simpan Nilai' }}
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ================== SCRIPT KIRIM SERTIFIKAT ================== --}}
<script>
  let sedangMengirim = false;

    document.addEventListener('DOMContentLoaded', function () {
    // Kirim Sertifikat
    document.querySelectorAll('.btn-kirim-sertifikat').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        if (sedangMengirim) return;
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
        .then(data => Swal.fire({
          icon: 'success',
          title: '🎉 Sukses!',
          text: data.message || 'Sertifikat berhasil dikirim ke email mahasiswa.',
          confirmButtonColor: '#28a745',
          allowOutsideClick: false,
          allowEscapeKey: false,
          backdrop: true
        }))
        .then(() => location.reload())
        .catch(error => Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: error.message || 'Terjadi kesalahan saat mengirim sertifikat.',
          confirmButtonColor: '#dc3545'
        }))
        .finally(() => {
          sedangMengirim = false;
          spinner.classList.add('d-none');
          btn.disabled = false;
        });
      });
    });

    // Tombol "Edit Nilai" disable logic
    document.querySelectorAll('.btn-disabled-edit').forEach(button => {
      button.addEventListener('click', function () {
        Swal.fire({
          icon: 'warning',
          title: 'Batas Edit Tercapai',
          text: 'Nilai sudah pernah diedit 2 kali, tidak bisa diedit lagi.',
          confirmButtonColor: '#ffc107'
        });
      });
    });
  });

</script>
