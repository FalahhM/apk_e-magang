<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dospem;
use App\Models\LaporanMagang;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DospemController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $dospem = $user->dospem;

        if (!$dospem) {
            // Misalnya arahkan ke halaman profil atau beri pesan error
            return redirect()->route('dospemdashboard')
                ->with('error', 'Data dospem tidak ditemukan.');
        }

        $mahasiswaIds = MahasiswaModel::where('dospem_id', $dospem->id)->pluck('id');

        $totalMahasiswa = $mahasiswaIds->count();
        $totalLaporan   = LaporanMagang::whereIn('mahasiswa_id', $mahasiswaIds)->count();
        $totalHadir     = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)->where('status', 'Hadir')->count();
        $totalIzin      = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)->where('status', 'Izin')->count();
        $totalAlfa      = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)->where('status', 'Alfa')->count();

        $year = now()->year;
        $labelsBulan = collect(range(1, 12))->map(function ($m) {
            return \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F');
        });

        $hadirPerBulan = [];
        $izinPerBulan  = [];
        $alfaPerBulan  = [];
        $laporanPerBulan = [];

        for ($m = 1; $m <= 12; $m++) {
            $hadirPerBulan[] = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereYear('tanggal', $year)->whereMonth('tanggal', $m)->where('status', 'Hadir')->count();
            $izinPerBulan[] = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereYear('tanggal', $year)->whereMonth('tanggal', $m)->where('status', 'Izin')->count();
            $alfaPerBulan[] = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereYear('tanggal', $year)->whereMonth('tanggal', $m)->where('status', 'Alfa')->count();

            $laporanPerBulan[] = LaporanMagang::whereIn('mahasiswa_id', $mahasiswaIds)
                ->whereYear('tanggal_kegiatan', $year)->whereMonth('tanggal_kegiatan', $m)->count();
        }
        
        $today = now()->toDateString();
        $sudahAbsenHariIni = Absensi::whereIn('mahasiswa_id', $mahasiswaIds)
            ->whereDate('tanggal', $today)->pluck('mahasiswa_id')->unique();

        $belumAbsenHariIni = MahasiswaModel::whereIn('id', $mahasiswaIds)
            ->whereNotIn('id', $sudahAbsenHariIni)->orderBy('nama_mahasiswa')->take(6)
            ->get(['id','nama_mahasiswa','nim']);

        $startOfWeek = now()->startOfWeek(); // Senin
        $endOfWeek   = now()->endOfWeek();   // Minggu
        $sudahLaporMingguIni = LaporanMagang::whereIn('mahasiswa_id', $mahasiswaIds)
            ->whereBetween('tanggal_kegiatan', [$startOfWeek, $endOfWeek])
            ->pluck('mahasiswa_id')->unique();

        $belumLaporMingguIni = MahasiswaModel::whereIn('id', $mahasiswaIds)
            ->whereNotIn('id', $sudahLaporMingguIni)->orderBy('nama_mahasiswa')->take(6)
            ->get(['id','nama_mahasiswa','nim']);

        return view('dospem.dashboard', [
            'totalMahasiswa' => $totalMahasiswa,
            'totalLaporan'   => $totalLaporan,
            'totalHadir'     => $totalHadir,
            'totalIzin'      => $totalIzin,
            'totalAlfa'      => $totalAlfa,

            'labelsBulan'    => $labelsBulan,
            'hadirPerBulan'  => $hadirPerBulan,
            'izinPerBulan'   => $izinPerBulan,
            'alfaPerBulan'   => $alfaPerBulan,
            'laporanPerBulan'=> $laporanPerBulan,

            'belumAbsenHariIni'  => $belumAbsenHariIni,
            'belumLaporMingguIni'=> $belumLaporMingguIni,
            'year' => $year,
        ]);
    }

    public function profil()
    {
        $dospem = Auth::user()
            ->dospem()
            ->with([
                'pengajuan.user' => function($q){
                    $q->where('role', 'kampus');
                },
                'mahasiswas'
            ])
            ->first();

        // Ambil nama kampus dari relasi
        $kampusName = $dospem->pengajuan && $dospem->pengajuan->user
            ? $dospem->pengajuan->user->name
            : '-';

        return view('dospem.profil', compact('dospem', 'kampusName'));
    }

    

    // DATA ABSENSI

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

    public function detailAbsensiMahasiswa(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        $absensiQuery = Absensi::where('mahasiswa_id', $mahasiswa->id);

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $absensiQuery->whereDate('tanggal', $request->tanggal);
        }

        // Filter status
        if ($request->filled('status')) {
            $absensiQuery->where('status', $request->status);
        }

        $absensi = $absensiQuery->orderBy('tanggal', 'desc')->get();

        return view('dospem.absensi.detailAbsensi', compact('absensi', 'mahasiswa'));
    }




    // DATA LAPORAN KEGIATAN MAGANG
    public function laporanMahasiswaBimbingan(Request $request)
    {
        $user = Auth::user();
        $dospem = $user->dospem;

        $query = MahasiswaModel::where('dospem_id', $dospem->id)
            ->with('laporanMagang');

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $mahasiswaList = $query->get();

        return view('dospem.laporan.dataLaporan', compact('mahasiswaList'));
    }

    public function detailLaporanMahasiswa(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        $query = LaporanMagang::where('mahasiswa_id', $mahasiswa->id);

        if ($request->tanggal) {
            $query->whereDate('tanggal_kegiatan', $request->tanggal);
        }

        $laporanList = $query->orderBy('tanggal_kegiatan', 'desc')->get();

        return view('dospem.laporan.detailLaporan', compact('laporanList', 'mahasiswa'));
    }

}
