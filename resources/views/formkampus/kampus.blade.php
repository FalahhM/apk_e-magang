<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Magang</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Form Pengajuan Magang</h2>
        <div><a href="/logout" class="btn btn-sm btn-secondary">Logout >></a></div>
        <form action="{{ route('mahasiswas.store') }}" method="POST">
            @csrf
            <!-- Asal Kampus -->
            <div class="form-group">
                <label for="asalKampus">Asal Kampus</label>
                <input type="text" class="form-control" id="asalKampus" value="{{ $user->name }}" readonly>
            </div>
            <!-- No Telp -->
            <div class="form-group">
                <label for="noTelp">No Telp</label>
                <input type="tel" class="form-control" id="noTelp" value="{{ $user->no_telp }}" readonly>
            </div>
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
            </div>
            <!-- Alamat Kampus -->
            <div class="form-group">
                <label for="alamatKampus">Alamat Kampus</label>
                <textarea class="form-control" id="alamatKampus" rows="3" readonly>{{ $user->alamat }}</textarea>
            </div>
            <!-- Contact Person -->
            <h5>Contact Person</h5>
            <div class="ml-5">
                <div class="form-group">
                    <label for="namaCP">Nama Contact Person</label>
                    <input type="text" class="form-control" id="namaCP" value="{{ $contact_person->namecp }}" readonly>
                </div>
                <div class="form-group">
                    <label for="emailCP">Email Contact Person</label>
                    <input type="email" class="form-control" id="emailCP" value="{{ $contact_person->emailcp }}" readonly>
                </div>
                <div class="form-group">
                    <label for="noHpCP">No HP Contact Person</label>
                    <input type="tel" class="form-control" id="noHpCP" value="{{ $contact_person->nohpcp }}" readonly>
                </div>
                <div class="form-group">
                    <label for="jabatanCP">Jabatan Contact Person</label>
                    <input type="text" class="form-control" id="jabatanCP" value="{{ $contact_person->jabatan }}" readonly>
                </div>
            </div>
            <!-- Mulai Tanggal -->
            <div class="form-group">
                <label for="mulaiTanggal">Mulai Tanggal</label>
                <input type="date" class="form-control" id="mulaiTanggal" name="mulai_tanggal">
            </div>
            <!-- Sampai Tanggal -->
            <div class="form-group">
                <label for="sampaiTanggal">Sampai Tanggal</label>
                <input type="date" class="form-control" id="sampaiTanggal" name="sampai_tanggal">
            </div>
            <div class="form-group">
                <label>Data Mahasiswa</label>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#studentModal">
                    Input Data
                </button>
            </div>

            <!-- Modal -->
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
                            <form action="{{ route('mahasiswas.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_mahasiswa">Nama Mahasiswa</label>
                                    <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" placeholder="Masukkan Nama Mahasiswa">
                                </div>
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM">
                                </div>
                                <div class="form-group">
                                    <label for="jurusan">Jurusan</label>
                                    <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Masukkan Jurusan">
                                </div>
                                <div class="form-group">
                                    <label for="dospem">Dospem</label>
                                    <input type="text" class="form-control" id="dospem" name="dospem" placeholder="Masukkan Nama Dospem">
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
            
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Dospem</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $index => $mahasiswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->jurusan }}</td>
                        <td>{{ $mahasiswa->dospem }}</td>
                        <td>
                            <a href="#"><button class="btn btn-warning">Edit</button></a>
                            <a href="#"><button class="btn btn-danger">Hapus</button></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Ajukan</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
