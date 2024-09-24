@extends('templateadmin.header')
@section('content')

<div class="container">
  <h3>Detail Pengajuan Magang</h3>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="row mb-4">
        <div class="col-md-3 d-flex justify-content-between">
          @if($pengajuan->status === 'Sedang Di Proses')
            <button type="button" class="btn btn-primary">Cetak</button>
            <button type="button" class="btn btn-success">Terima</button>
            <button type="button" class="btn btn-danger">Tolak</button>
          @elseif($pengajuan->status === 'Pending')
            <form action="{{ route('prosesPengajuan', ['id' => $pengajuan->id]) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary">Proses</button>
            </form>
          @endif
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <h5>No. Surat:</h5>
          <p>{{ $pengajuan->no_surat }}</p>
        </div>
        <div class="col-md-6">
          <h5>Tanggal Pengajuan:</h5>
          <p>({{ \Carbon\Carbon::parse($pengajuan->tanggal_surat)->format('d/m/Y') }})</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <h5>Perihal:</h5>
          <p>{{ $pengajuan->perihal }}</p>
        </div>
        <div class="col-md-6">
          <h5>Periode:</h5>
          <p>({{ \Carbon\Carbon::parse($pengajuan->mulai_tanggal)->format('d/m/Y') }}) - ({{ \Carbon\Carbon::parse($pengajuan->sampai_tanggal)->format('d/m/Y') }})</p>
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
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
