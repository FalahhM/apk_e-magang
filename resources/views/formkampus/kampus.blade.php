@extends('template.header')
@section('content')

    <div class="container">

        <!-- Form Title -->
        <h2 class="mb-3">Data Kampus</h2>
        
        <!-- Campus Data Section -->
        <form action="{{ url('/mahasiswa') }}" method="POST">
            @csrf
            <!-- Campus Name -->
            <div class="form-group">
                <label for="asalKampus">Asal Kampus</label>
                <input type="text" class="form-control" id="asalKampus" value="{{ $user->name }}" readonly>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="noTelp">No Telp</label>
                <input type="tel" class="form-control" id="noTelp" value="{{ $user->no_telp }}" readonly>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
            </div>

            <!-- Campus Address -->
            <div class="form-group">
                <label for="alamatKampus">Alamat Kampus</label>
                <textarea class="form-control" id="alamatKampus" rows="3" readonly>{{ $user->alamat }}</textarea>
            </div>

            <!-- Contact Person Section -->
            <h4 class="mt-3 mb-3">Contact Person</h4>
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
@endsection
