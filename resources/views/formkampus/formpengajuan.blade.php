@extends('template.header')
@section('content')

<style>
    body {
        background-color: #eef7ee;
    }

    h4, h5 {
        color: #2e7d32;
        font-weight: bold;
    }

    .card-header {
        background-color: #388e3c;
        color: white;
        font-weight: 600;
    }

    .btn-success {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }

    .btn-success:hover {
        background-color: #256428;
        border-color: #256428;
    }

    .btn-primary {
        background-color: #388e3c;
        border-color: #388e3c;
    }

    .btn-primary:hover {
        background-color: #2e7d32;
        border-color: #2e7d32;
    }

    label {
        font-weight: 500;
    }
</style>

<div class="container py-4">
    <div class="card shadow mb-4">
        <div class="card-header">📄 Form Pengajuan Magang</div>
        <div class="card-body">
            <form action="{{ route('storePengajuan') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
                @csrf

                <div class="mb-3">
                    <label for="no_surat">No. Surat</label>
                    <input type="text" class="form-control" id="no_surat" name="no_surat" placeholder="Isi Nomor Surat" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_surat">Tanggal Surat</label>
                    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                </div>

                <div class="mb-3">
                    <label for="perihal">Perihal</label>
                    <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Isi Perihal" required>
                </div>

                <div class="mb-3">
                    <label for="mulai_tanggal">Mulai Tanggal</label>
                    <input type="date" class="form-control" id="mulai_tanggal" name="mulai_tanggal" required>
                </div>

                <div class="mb-3">
                    <label for="sampai_tanggal">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" required>
                </div>

                <div class="form-group mt-4">
                    <label for="dokumen">Unggah Surat Permohonan</label>
                    <input type="file" class="form-control-file" id="dokumen" name="dokumen" accept="application/pdf">
                    <small class="form-text text-muted">Format: .pdf</small>

                    {{-- Preview PDF --}}
                    <iframe id="previewPdf" src="" style="width:100%; height:400px; display:none;" class="mt-3 border rounded"></iframe>
                </div>


                <div class="card shadow mb-4">
                    <div class="card-header">🎓 Data Mahasiswa</div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#mahasiswaModal">
                            ➕ Tambah Mahasiswa
                        </button>

                        <table class="table table-bordered">
                            <thead class="table-success text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>NIM</th>
                                    <th>Jurusan</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Email Dosen Pembimbing</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mahasiswaTable">
                                <!-- Data Mahasiswa akan ditambahkan di sini -->
                            </tbody>
                        </table>

                        <input type="hidden" id="mahasiswa" name="mahasiswa">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">📤 Kirim Pengajuan</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Mahasiswa -->
<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="mahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="mahasiswaModalLabel">Tambah / Edit Mahasiswa</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email Mahasiswa" required>
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

                    <div class="form-group">
                        <label for="email_dospem">Email Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="email_dospem" placeholder="Email Dosen Pembimbing" required>
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
    let mahasiswaList = [];

    document.getElementById('simpanMahasiswa').addEventListener('click', function () {
    const nama = document.getElementById('nama').value;
    const email = document.getElementById('email').value;
    const nim = document.getElementById('nim').value;
    const jurusan = document.getElementById('jurusan').value;
    const dospem_nama = document.getElementById('dospem').value; // ubah ke dospem_nama
    const dospem_email = document.getElementById('email_dospem').value; // ubah ke dospem_email
    const index = document.getElementById('indexMahasiswa').value;

    if (!nama || !email || !nim || !jurusan) {
        alert('Nama, Email, NIM, dan Jurusan wajib diisi.');
        return;
    }

    // Dospem boleh kosong
    const mahasiswa = { 
        nama, 
        email, 
        nim, 
        jurusan, 
        dospem_nama, 
        dospem_email 
    };

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
    tbody.innerHTML = '';
    mahasiswaList.forEach((mhs, index) => {
        tbody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${mhs.nama}</td>
                <td>${mhs.email}</td>
                <td>${mhs.nim}</td>
                <td>${mhs.jurusan}</td>
                <td>${mhs.dospem_nama || '-'}</td>
                <td>${mhs.dospem_email || '-'}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editMahasiswa(${index})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusMahasiswa(${index})">Hapus</button>
                </td>
            </tr>
        `;
    });

    document.getElementById('mahasiswa').value = JSON.stringify(mahasiswaList);
}

function editMahasiswa(index) {
    const m = mahasiswaList[index];
    document.getElementById('nama').value = m.nama;
    document.getElementById('email').value = m.email;
    document.getElementById('nim').value = m.nim;
    document.getElementById('jurusan').value = m.jurusan;
    document.getElementById('dospem').value = m.dospem_nama || '';
    document.getElementById('email_dospem').value = m.dospem_email || '';
    document.getElementById('indexMahasiswa').value = index;
    $('#mahasiswaModal').modal('show');
}


    function hapusMahasiswa(index) {
        if (confirm('Yakin ingin menghapus data mahasiswa ini?')) {
            mahasiswaList.splice(index, 1);
            renderMahasiswaTable();
        }
    }

    function resetForm() {
        document.getElementById('formMahasiswa').reset();
        document.getElementById('indexMahasiswa').value = '';
    }
</script>


<script>
    document.getElementById('dokumen').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file && file.type === "application/pdf") {
            const url = URL.createObjectURL(file);
            const previewFrame = document.getElementById('previewPdf');
            previewFrame.src = url;
            previewFrame.style.display = 'block';
        } else {
            alert('Silakan unggah file PDF!');
            document.getElementById('previewPdf').style.display = 'none';
        }
    });
</script>


@endsection
