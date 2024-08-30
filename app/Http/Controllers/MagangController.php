<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\MahasiswaModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    function nampil(){
        $userId = Auth::id();
        $user = User::find($userId);
        $contact_person = ContactPerson::where('user_id', $userId)->first();

        return view('formkampus/kampus', compact('user','contact_person'));
    }

    public function mahasiswa(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'nim' => 'required|string|max:20',
            'jurusan' => 'required|string|max:100',
            'dospem' => 'required|string|max:255',
            'mulai_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:mulai_tanggal',
        ]);

        MahasiswaModel::create([
            'nama_mahasiswa' => $request->input('nama_mahasiswa'),
            'nim' => $request->input('nim'),
            'jurusan' => $request->input('jurusan'),
            'dospem' => $request->input('dospem'),
            'mulai_tanggal' => $request->input('mulai_tanggal'),
            'sampai_tanggal' => $request->input('sampai_tanggal'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    public function datatabel()
    {
        $mahasiswas = MahasiswaModel::all(); // Perbaiki variabel nama
        return view('formkampus/kampus', compact('mahasiswas'));
    }
}

