@extends('templateadmin.header')
@section('content')

<body class="bg-light">
  <div class="container my-5">

    {{-- Greeting Section --}}
    <div class="bg-white border rounded shadow-sm p-4 mb-4">
      <h2 class="text-success">Halo Admin!</h2>
      <p>Selamat datang di halaman Dashboard E-Magang <strong>PT Perkebunan Nusantara IV Regional IV</strong></p>
    </div>

    {{-- Statistik Section --}}
    <div class="row">
      {{-- Total Pengajuan --}}
      <div class="col-md-3 mb-3">
        <div class="card border-left-primary shadow h-100 py-3">
          <div class="card-body text-center">
            <h5 class="text-primary font-weight-bold">Total Pengajuan</h5>
            <h2>{{ $total_pengajuan }}</h2>
          </div>
        </div>
      </div>

      {{-- Diterima --}}
      <div class="col-md-3 mb-3">
        <div class="card border-left-success shadow h-100 py-3">
          <div class="card-body text-center">
            <h5 class="text-success font-weight-bold">Diterima</h5>
            <h2>{{ $pengajuan_diterima }}</h2>
          </div>
        </div>
      </div>

      {{-- Ditolak --}}
      <div class="col-md-3 mb-3">
        <div class="card border-left-danger shadow h-100 py-3">
          <div class="card-body text-center">
            <h5 class="text-danger font-weight-bold">Ditolak</h5>
            <h2>{{ $pengajuan_ditolak }}</h2>
          </div>
        </div>
      </div>

      {{-- Sedang Diproses --}}
      <div class="col-md-3 mb-3">
        <div class="card border-left-warning shadow h-100 py-3">
          <div class="card-body text-center">
            <h5 class="text-warning font-weight-bold">Diproses</h5>
            <h2>{{ $pengajuan_diproses }}</h2>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>

@endsection
