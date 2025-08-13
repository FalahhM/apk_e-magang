@extends('templatedospem.header')

@section('title', 'Dashboad Dospem')

@section('content')


<div class="container py-4">
  <h3 class="fw-bold text-success">Selamat Datang, {{ auth()->user()->name }}</h3>
  <p>Ini adalah halaman khusus mahasiswa magang</p>
</div>

@endsection
