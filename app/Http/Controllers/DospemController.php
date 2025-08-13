<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dospem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DospemController extends Controller
{
    public function index()
    {
        return view('dospem.dashboard');
    }

    public function absensiMahasiswaBimbingan(Request $request)
    {
        $user = Auth::user();
        $dospem = $user->dospem;

        $query = \App\Models\MahasiswaModel::where('dospem_id', $dospem->id);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $mahasiswaList = $query->get();

        return view('dospem.absensi.dataAbsensi', compact('mahasiswaList'));
    }

    public function detailAbsensiMahasiswa($id)
    {
        $mahasiswa = \App\Models\MahasiswaModel::findOrFail($id);

        $absensi = \App\Models\Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dospem.absensi.detailAbsensi', compact('absensi', 'mahasiswa'));
    }


}
