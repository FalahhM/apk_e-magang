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

        <div class="form-group">
            <label for="mulai_tanggal">Mulai Tanggal</label>
            <input type="date" class="form-control" id="mulai_tanggal" name="mulai_tanggal"  required>
        </div>

        <div class="form-group">
            <label for="sampai_tanggal">Sampai Tanggal</label>
            <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" required>
        </div>

        <div class="form-group mt-4">
            <label for="dokumen">Unggah Surat Permohonan</label>
            <input type="file" class="form-control-file" id="dokumen" name="dokumen" accept="application/pdf">
            <small class="form-text text-muted">Format: .pdf</small>
        </div>

        <h4 class="mt-5">Data Mahasiswa</h4>
        <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#mahasiswaModal">
            Tambah Mahasiswa
        </button>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Dosen Pembimbing</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="mahasiswaTable">
                <!-- Data Mahasiswa akan ditambahkan di sini -->
            </tbody>
        </table>

        <input type="hidden" id="mahasiswa" name="mahasiswa"> <!-- Field hidden untuk data mahasiswa JSON -->

        <button type="submit" class="btn btn-success mt-3">Kirim Pengajuan</button>
    </div>
</form>

<!-- Modal untuk Menambah/Mengedit Mahasiswa -->
<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah/Mengedit Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formMahasiswa">
                    <input type="hidden" id="indexMahasiswa">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" placeholder="Nama Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" placeholder="NIM Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <input type="text" class="form-control" id="jurusan" placeholder="Jurusan Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="dospem">Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="dospem" placeholder="Dosen Pembimbing" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetForm()">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanMahasiswa">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let mahasiswaList = []; // Menyimpan daftar mahasiswa sementara sebelum dikirim ke server

    document.getElementById('simpanMahasiswa').addEventListener('click', function() {
        const nama = document.getElementById('nama').value;
        const nim = document.getElementById('nim').value;
        const jurusan = document.getElementById('jurusan').value;
        const dospem = document.getElementById('dospem').value;
        const index = document.getElementById('indexMahasiswa').value;

        // Validasi input
        if (!nama || !nim || !jurusan || !dospem) {
            alert('Semua field harus diisi');
            return;
        }

        const mahasiswa = { nama, nim, jurusan, dospem };

        // Tambah atau edit mahasiswa
        if (index === '') {
            mahasiswaList.push(mahasiswa);
        } else {
            mahasiswaList[index] = mahasiswa;
        }

        renderMahasiswaTable();
        resetForm();
        $('#mahasiswaModal').modal('hide');
    });

    function renderMahasiswaTable() {
        const tbody = document.getElementById('mahasiswaTable');
        tbody.innerHTML = ''; // Bersihkan tabel
        mahasiswaList.forEach((mahasiswa, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${mahasiswa.nama}</td>
                <td>${mahasiswa.nim}</td>
                <td>${mahasiswa.jurusan}</td>
                <td>${mahasiswa.dospem}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editMahasiswa(${index})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusMahasiswa(${index})">Hapus</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Simpan data mahasiswa ke input hidden
        document.getElementById('mahasiswa').value = JSON.stringify(mahasiswaList);
    }

    function editMahasiswa(index) {
        const mahasiswa = mahasiswaList[index];
        document.getElementById('nama').value = mahasiswa.nama;
        document.getElementById('nim').value = mahasiswa.nim;
        document.getElementById('jurusan').value = mahasiswa.jurusan;
        document.getElementById('dospem').value = mahasiswa.dospem;
        document.getElementById('indexMahasiswa').value = index;
        $('#mahasiswaModal').modal('show');
    }

    function hapusMahasiswa(index) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            mahasiswaList.splice(index, 1);
            renderMahasiswaTable();
        }
    }

    function resetForm() {
        document.getElementById('formMahasiswa').reset();
        document.getElementById('indexMahasiswa').value = '';
    }
</script>

@endsection
    