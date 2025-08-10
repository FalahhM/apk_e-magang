<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dashboard');
    }

    public function absensiForm(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $mahasiswaId = Auth::id();

        // Cek apakah sudah absen hari ini
        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
                                ->whereDate('tanggal', $today)
                                ->exists();

        // Filter tanggal (kalau ada di request)
        $query = Absensi::where('mahasiswa_id', $mahasiswaId)->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $riwayatAbsensi = $query->get();

        return view('mahasiswa.absensi', [
            'alreadyAbsent' => $alreadyAbsent,
            'today' => $today,
            'riwayatAbsensi' => $riwayatAbsensi,
            'filterTanggal' => $request->tanggal // kirim ke view
        ]);
    }


    public function absensiStore(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $today = Carbon::today()->toDateString();
        $mahasiswaId = Auth::id();

        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
                                ->whereDate('tanggal', $today)
                                ->exists();

        if ($alreadyAbsent) {
            return redirect()->back()->with('error', 'Kamu sudah absen hari ini.');
        }

        // Jika status hadir dan keterangan kosong, isi default
        $keterangan = $request->keterangan;
        if ($request->status === 'hadir' && empty($keterangan)) {
            $keterangan = 'Hadir melaksanakan magang';
        }

        Absensi::create([
            'mahasiswa_id' => $mahasiswaId,
            'tanggal' => $today,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('mahasiswa.absensi')->with('success', 'Absensi berhasil disimpan.');
    }
}
