<div class="modal fade" id="isiModal{{ $mahasiswa->id }}" tabindex="-1" aria-labelledby="isiModalLabel{{ $mahasiswa->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="isiModalLabel{{ $mahasiswa->id }}">
          Isi Nilai - {{ $mahasiswa->nama_mahasiswa }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <form action="{{ route('laporan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">

        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <p><strong>Nama:</strong> {{ $mahasiswa->nama_mahasiswa }}</p>
          <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
          <p><strong>Universitas:</strong> {{ $mahasiswa->pengajuan->user->name ?? '-' }}</p>
          <hr>
            <h6>Daftar Laporan Kegiatan:</h6>
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
          <hr>

          <div class="row">
            @php
              $komponen = [
                'integritas' => 'Integritas',
                'ketepatan_waktu' => 'Ketepatan Waktu',
                'keahlian' => 'Keahlian',
                'teamwork' => 'Team Work',
                'komunikasi' => 'Komunikasi',
                'teknologi' => 'Teknologi',
                'pengembangan_diri' => 'Pengembangan Diri',
              ];
            @endphp

            @foreach($komponen as $field => $label)
              <div class="col-md-6 mb-3">
                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                <input type="number" class="form-control" name="{{ $field }}" id="{{ $field }}" required min="0" max="100">
              </div>
            @endforeach
          </div>
        </div>

        <div class="modal-footer py-1">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
