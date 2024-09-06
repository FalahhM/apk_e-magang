@extends('templateadmin.header')
@section('content')

<div class="container">

  <h3>Pengajuan Magang</h3>
  <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Surat</th>
                                            <th>Nama Kampus</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Perihal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    @if($data_pengajuan->isNotEmpty())
                                    <tbody>
                                        @foreach ($data_pengajuan as $key => $pengajuan)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $pengajuan->no_surat }}</td>
                                            <td>{{ $pengajuan->user->name }}</td>
                                            <td>{{ $pengajuan->tanggal_surat }}</td>
                                            <td>{{ $pengajuan->perihal }}</td>
                                            <td>{{ $pengajuan->status ?? 'Belum diproses' }}</td>
                                            <td><button class="btn btn-info btn-sm">Detail</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @else
                                        <p>Data pengajuan tidak tersedia.</p>
                                    @endif
                                </table>
                            </div>
                        </div>
  </div>

</div>

@endsection