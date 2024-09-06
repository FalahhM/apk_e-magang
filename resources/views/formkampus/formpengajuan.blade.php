@extends('template.header')
@section('content')

<form action="{{ route('storePengajuan') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
    @csrf
    <div class="container">
        <h4 class="mb-3">Form Pengajuan Magang</h4>

        <div class="form-group">
            <label for="no_surat">No. Surat</label>
            <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="Isi Nomor Surat">
        </div>

        <div class="form-group">
            <label for="tanggal_surat">Tanggal Surat</label>
            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat">
        </div>

        <div class="form-group">
            <label for="perihal">Perihal</label>
            <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Isi Perihal">
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
                            <button type="button" class="btn btn-danger" onclick="hapusMahasiswa('{{ route('hapusMahasiswa', $mahasiswa->id) }}')">H</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data Mahasiswa Tidak Tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</form>

<!-- Student Input Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('storeMahasiswa') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Input Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function hapusMahasiswa(url) {
    if (confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')) {
        const form = document.createElement('form');
        form.action = url; // Pastikan ini mengarah ke route hapusMahasiswa
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

$('.editButton').on('click', function() {
    let id = $(this).data('id');
    $.get('/path/to/get/mahasiswa/' + id, function(data) {
        $('#editForm').attr('action', '/path/to/update/mahasiswa/' + id);
        $('#editId').val(data.id);
        $('#edit_nama_mahasiswa').val(data.nama_mahasiswa);
        $('#edit_nim').val(data.nim);
        $('#edit_jurusan').val(data.jurusan);
        $('#edit_dospem').val(data.dospem);
        $('#edit_mulaiTanggal').val(data.mulai_tanggal);
        $('#edit_sampaiTanggal').val(data.sampai_tanggal);
    });
});
</script>

@endsection
