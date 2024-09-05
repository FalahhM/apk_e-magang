@extends('template.header')
@section('content')

<div class="container ">

  <h4 class="mb-3">Form Pengajuan Magang</h4>

    <div class="form-group">
      <label for="no_surat">No. Surat</label>
      <input type="text" class="form-control" id="no_surat" placeholder="Isi Nomor Surat">
    </div>

    <div class="form-group">
      <label for="tanggal_surat">Tanggal Surat</label>
      <input type="date" class="form-control" id="tanggal_surat">
    </div>

    <div class="form-group">
      <label for="perihal">Perihal</label>
      <input type="text" class="form-control" id="perihal" placeholder="Isi Perihal">
    </div>

    <div class="form-group mt-4">
        <label for="dokumen">Unggah Surat Permohonan</label>
        <input type="file" class="form-control-file" id="dokumen" name="dokumen" accept="application/pdf" multiple>
        <small class="form-text text-muted">Format dokumen: PDF</small>
    </div>

    <div class="form-group">
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-info mt-2" data-toggle="modal" data-target="#studentModal">
          Tambah Mahasiswa
      </button>
    </div>

    <!-- Student Input Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Input Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/mahasiswa') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" placeholder="Masukkan Nama Mahasiswa" required>
                        </div>
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM" required>
                        </div>
                        <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Masukkan Jurusan" required>
                        </div>
                        <div class="form-group">
                            <label for="dospem">Dospem</label>
                            <input type="text" class="form-control" id="dospem" name="dospem" placeholder="Masukkan Nama Dospem" required>
                        </div>
                        <div class="form-group">
                            <label for="mulaiTanggal">Mulai Tanggal</label>
                            <input type="date" class="form-control" id="mulaiTanggal" name="mulai_tanggal">
                        </div>
                        <div class="form-group">
                            <label for="sampaiTanggal">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="sampaiTanggal" name="sampai_tanggal">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table for Students Data -->
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Mahasiswa</th>
          <th>NIM</th>
          <th>Jurusan</th>
          <th>Dospem</th>
          <th>Mulai Tanggal</th>
          <th>Sampai Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($data_mahasiswa as $key => $mahasiswa)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $mahasiswa->nama_mahasiswa }}</td>
          <td>{{ $mahasiswa->nim }}</td>
          <td>{{ $mahasiswa->jurusan }}</td>
          <td>{{ $mahasiswa->dospem }}</td>
          <td>{{ $mahasiswa->mulai_tanggal }}</td>
          <td>{{ $mahasiswa->sampai_tanggal }}</td>
          <td>
            <button type="button" class="btn btn-warning editButton" data-id="{{ $mahasiswa->id }}" data-toggle="modal" data-target="#editModal">E</button>
            <form action="{{ route('hapusMahasiswa', $mahasiswa->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">H</button>
            </form>
          </td>
        </tr>
          @empty
        <tr>
          <td colspan="8" class="text-center">Data Mahasiswa Tidak Tersedia</td>
        </tr>
          @endforelse
      </tbody>
    </table>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="edit_nama_mahasiswa">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="edit_nama_mahasiswa" name="nama_mahasiswa">
                        </div>
                        <div class="form-group">
                            <label for="edit_nim">NIM</label>
                            <input type="text" class="form-control" id="edit_nim" name="nim">
                        </div>
                        <div class="form-group">
                            <label for="edit_jurusan">Jurusan</label>
                            <input type="text" class="form-control" id="edit_jurusan" name="jurusan">
                        </div>
                        <div class="form-group">
                            <label for="edit_dospem">Dospem</label>
                            <input type="text" class="form-control" id="edit_dospem" name="dospem">
                        </div>
                        <div class="form-group">
                            <label for="edit_mulaiTanggal">Mulai Tanggal</label>
                            <input type="date" class="form-control" id="edit_mulaiTanggal" name="mulai_tanggal">
                        </div>
                        <div class="form-group">
                            <label for="edit_sampaiTanggal">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="edit_sampaiTanggal" name="sampai_tanggal">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</div>
  @endsection