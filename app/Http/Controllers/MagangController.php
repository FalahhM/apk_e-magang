<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\MahasiswaModel;
use App\Models\PengajuanModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    // Menampilkan halaman kampus
    public function nampil()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $contact_person = ContactPerson::where('user_id', $userId)->first();
        
        return view('formkampus.kampus', compact('user', 'contact_person'));
    }

    // Menampilkan form pengajuan
    public function pengajuan()
    {
        $userId = Auth::id();
        $user = User::find($userId);

        return view('formkampus.formpengajuan', compact('user'));
    }

    // Menyimpan pengajuan ke database
    public function storePengajuan(Request $request)
    {
        // Validasi data pengajuan
        $validatedData = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'dokumen' => 'nullable|file|mimes:pdf',
            'mahasiswa' => 'nullable|string', // ubah menjadi string karena data dikirim dalam bentuk JSON string
        ]);

        $validatedData['user_id'] = Auth::id();

        // Simpan dokumen jika ada
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validatedData['dokumen'] = $filename;
        }

        // Simpan data pengajuan
        $pengajuan = PengajuanModel::create($validatedData);

        // Simpan data mahasiswa jika ada
        if ($request->filled('mahasiswa')) {
            // Decode JSON mahasiswa menjadi array
            $mahasiswaList = json_decode($request->input('mahasiswa'), true);
            foreach ($mahasiswaList as $mahasiswa) {
                MahasiswaModel::create([
                    'pengajuan_id' => $pengajuan->id,
                    'nama_mahasiswa' => $mahasiswa['nama'],
                    'nim' => $mahasiswa['nim'],
                    'jurusan' => $mahasiswa['jurusan'],
                    'dospem' => $mahasiswa['dospem'],
                    'mulai_tanggal' => $mahasiswa['mulaiTanggal'],
                    'sampai_tanggal' => $mahasiswa['sampaiTanggal']
                ]);
            }
        }

        // Redirect dengan pesan sukses
        return redirect()->route('formPengajuan')->with('success', 'Pengajuan berhasil disimpan!');
    }
}
