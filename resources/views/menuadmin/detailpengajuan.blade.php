@extends('templateadmin.header')
@section('content')

<div class="container">
  <h3>Detail Pengajuan Magang</h3>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-6">
          <h5>No. Surat:</h5>
          <p>{{ $pengajuan->no_surat }}</p>
        </div>
        <div class="col-md-6">
          <h5>Tanggal Pengajuan:</h5>
          <p>{{ $pengajuan->tanggal_surat }}</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <h5>Perihal:</h5>
          <p>{{ $pengajuan->perihal }}</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <h5>Surat:</h5>
          @if($pengajuan->dokumen)
            <a href="{{ asset('uploads/' . $pengajuan->dokumen) }}" target="_blank" class="btn btn-primary">Lihat Surat</a>
          @else
            <p>Dokumen tidak tersedia</p>
          @endif
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <h5>Data Kampus</h5>
          <p><strong>Nama Kampus:</strong> {{ $pengajuan->user->name }}</p>
          <p><strong>No. Telp:</strong> {{ $pengajuan->user->no_telp }}</p>
          <p><strong>Email:</strong> {{ $pengajuan->user->email }}</p>
          <p><strong>Alamat Kampus:</strong> {{ $pengajuan->user->alamat }}</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <h5>Contact Person</h5>
          <p><strong>Nama:</strong> {{ $pengajuan->user->contactPerson->namecp }}</p>
          <p><strong>Email:</strong> {{ $pengajuan->user->contactPerson->emailcp }}</p>
          <p><strong>No. HP:</strong> {{ $pengajuan->user->contactPerson->nohpcp }}</p>
          <p><strong>Jabatan:</strong> {{ $pengajuan->user->contactPerson->jabatan }}</p>
        </div>
      </div>

      <!-- Tabel Data Mahasiswa -->
      <div class="row mb-3">
        <div class="col-md-12">
          <h5>Data Mahasiswa</h5>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Dosen Pembimbing</th>
                <th>Periode</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pengajuan->mahasiswas as $index => $mahasiswa)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                  <td>{{ $mahasiswa->nim }}</td>
                  <td>{{ $mahasiswa->jurusan }}</td>
                  <td>{{ $mahasiswa->dospem }}</td>
                  <td>({{ $mahasiswa->mulai_tanggal }}) - ({{ $mahasiswa->sampai_tanggal }})</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <form action="" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-success">Terima</button>
          </form>

          <form action="" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger">Tolak</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
