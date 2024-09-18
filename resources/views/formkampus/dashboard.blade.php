@extends('template.header')
@section('content')

<div class="container">
  <h2 class="mb-4">Dashboard Kampus</h2>

  {{-- Data Kampus --}}
  <div class="card mb-4">
    <div class="card-header">Data Kampus</div>
    <div class="card-body">
      <p><strong>Nama Kampus: </strong>{{ $user->name }}</p>
      <p><strong>No Telp: </strong>{{ $user->no_telp }}</p>
      <p><strong>Email: </strong>{{ $user->email }}</p>
      <p><strong>Alamat Kampus: </strong>{{ $user->alamat }}</p>
    </div>
  </div>

  {{-- Data Contact Person --}}
  <div class="card mb-4">
    <div class="card-header">Contact Person</div>
    <div class="card-body">
      <p><strong>Nama: </strong>{{ $contact_person->namecp }}</p>
      <p><strong>Email: </strong>{{ $contact_person->emailcp }}</p>
      <p><strong>No HP: </strong>{{ $contact_person->nohpcp }}</p>
      <p><strong>Jabatan: </strong>{{ $contact_person->jabatan }}</p>
    </div>
  </div>

  {{-- Data Pengajuan Magang --}}
  <div class="card">
    <div class="card-header">Daftar Pengajuan Magang</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>No. Surat</th>
            <th>Tanggal Surat</th>
            <th>Perihal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pengajuan as $pengajuan)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $pengajuan->no_surat }}</td>
              <td>{{ $pengajuan->tanggal_surat }}</td>
              <td>{{ $pengajuan->perihal }}</td>
              <td>{{ $pengajuan->status }}</td>
              <td>
                <a href="" class="btn btn-info btn-sm">Detail</a>
              </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

@endsection