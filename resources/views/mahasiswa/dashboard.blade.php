@extends('templatemahasiswa.header')

@section('title', 'Dashboad Mahasiswa')

@section('content')


<div class="container py-4">
  <h3 class="fw-bold text-success">Selamat Datang, {{ auth()->user()->name }}</h3>
  <p>Ini adalah halaman khusus mahasiswa magang</p>

  <div class="mt-4">
    <a href="{{ url('/mahasiswa/absensi') }}" class="btn btn-success me-2">Absensi</a>
    <a href="{{ url('/mahasiswa/laporan') }}" class="btn btn-success me-2">Laporan Magang</a>
    <a href="{{ url('/mahasiswa/sertifikat') }}" class="btn btn-success me-2">Sertifikat</a>
  </div>
</div>

@endsection
