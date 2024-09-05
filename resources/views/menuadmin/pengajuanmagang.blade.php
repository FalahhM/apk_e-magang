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
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="btn btn-info btn-sm">Detail</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
  </div>

</div>

@endsection