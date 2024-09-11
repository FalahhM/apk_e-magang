@extends('template.header')
@section('content')

<form action="{{ route('storePengajuan') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
    @csrf
    <div class="container">
        <h4 class="mb-3">Form Pengajuan Magang</h4>

        <div class="form-group">
            <label for="no_surat">No. Surat</label>
            <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="Isi Nomor Surat" required>
        </div>

        <div class="form-group">
            <label for="tanggal_surat">Tanggal Surat</label>
            <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
        </div>

        <div class="form-group">
            <label for="perihal">Perihal</label>
            <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Isi Perihal" required>
        </div>

        <div class="form-group mt-4">
            <label for="dokumen">Unggah Surat Permohonan</label>
            <input type="file" class="form-control-file" id="dokumen" name="dokumen" accept="application/pdf">
            <small class="form-text text-muted">Format dokumen: PDF</small>
        </div>

        <input type="hidden" id="mahasiswaInput" name="mahasiswa">

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
            <tbody id="studentTableBody">
                <!-- Data mahasiswa akan di-render di sini -->
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</form>

<!-- Student Input Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="studentForm">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveStudent">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let mahasiswaData = [];

    function renderTable() {
        const tbody = document.getElementById('studentTableBody');
        tbody.innerHTML = '';
        mahasiswaData.forEach((mahasiswa, index) => {
            let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${mahasiswa.nama}</td>
                    <td>${mahasiswa.nim}</td>
                    <td>${mahasiswa.jurusan}</td>
                    <td>${mahasiswa.dospem}</td>
                    <td>${mahasiswa.mulaiTanggal}</td>
                    <td>${mahasiswa.sampaiTanggal}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="editMahasiswa(${index})">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeMahasiswa(${index})">Hapus</button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });

        // Update hidden input with JSON string
        document.getElementById('mahasiswaInput').value = JSON.stringify(mahasiswaData);
    }

    document.getElementById('saveStudent').addEventListener('click', function() {
        const form = document.getElementById('studentForm');
        const nama = form.querySelector('#nama_mahasiswa').value;
        const nim = form.querySelector('#nim').value;
        const jurusan = form.querySelector('#jurusan').value;
        const dospem = form.querySelector('#dospem').value;
        const mulaiTanggal = form.querySelector('#mulaiTanggal').value;
        const sampaiTanggal = form.querySelector('#sampaiTanggal').value;

        mahasiswaData.push({
            nama: nama,
            nim: nim,
            jurusan: jurusan,
            dospem: dospem,
            mulaiTanggal: mulaiTanggal,
            sampaiTanggal: sampaiTanggal
        });

        renderTable();
        $('#studentModal').modal('hide');
        form.reset();
    });

    window.editMahasiswa = function(index) {
        const mahasiswa = mahasiswaData[index];
        document.getElementById('nama_mahasiswa').value = mahasiswa.nama;
        document.getElementById('nim').value = mahasiswa.nim;
        document.getElementById('jurusan').value = mahasiswa.jurusan;
        document.getElementById('dospem').value = mahasiswa.dospem;
        document.getElementById('mulaiTanggal').value = mahasiswa.mulaiTanggal;
        document.getElementById('sampaiTanggal').value = mahasiswa.sampaiTanggal;

        $('#studentModal').modal('show');
        mahasiswaData.splice(index, 1);
        renderTable();
    };

    window.removeMahasiswa = function(index) {
        mahasiswaData.splice(index, 1);
        renderTable();
    };
});
</script>

@endsection
