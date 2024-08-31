<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\MahasiswaModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    public function nampil()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $contact_person = ContactPerson::where('user_id', $userId)->first();
        $data_mahasiswa = MahasiswaModel::where('user_id', $userId)->get();

        return view('formkampus.kampus', compact('user', 'contact_person','data_mahasiswa'));
    }

    public function storeMahasiswa(Request $request){
        $validatedData = $request->validate([
            'nama_mahasiswa' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'jurusan' => 'required|string|max:255',
            'dospem' => 'required|string|max:255',
            'mulai_tanggal' => 'nullable|date',
            'sampai_tanggal' => 'nullable|date',
        ]);
        $validatedData['user_id'] = Auth::id();
        MahasiswaModel::create($validatedData);
        return redirect()->back()->with('success', 'Data mahasiswa berhasil disimpan.');
    }
}