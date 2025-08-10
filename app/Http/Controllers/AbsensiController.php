<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Absensi;
use App\Models\User;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\PengajuanModel;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggalHariIni = Carbon::today();
        $search = $request->search;
        $kampusId = $request->kampus_id;

        // Ambil daftar kampus untuk filter dropdown
        $listKampus = User::where('role', 'kampus')
        ->orderBy('name')
        ->get();

        // Ambil semua mahasiswa yang punya pengajuan magang
       $mahasiswaQuery = MahasiswaModel::with('kampus', 'pengajuan')
        ->whereHas('pengajuan', function ($q) {
            $q->whereNotNull('mulai_tanggal')
            ->whereNotNull('sampai_tanggal')
            ->where('status', 'Diterima');
        });


        // Filter kampus jika ada
        if ($kampusId) {
            $mahasiswaQuery->where('user_id', $kampusId);
        }

        // Filter nama jika ada
        if ($search) {
            $mahasiswaQuery->where('nama_mahasiswa', 'like', '%' . $search . '%');
        }

        $mahasiswas = $mahasiswaQuery->get();

        // Mahasiswa yang sudah absen hari ini
        $sudahAbsen = $mahasiswas->filter(function ($mhs) use ($tanggalHariIni) {
            return Absensi::where('mahasiswa_id', $mhs->id)
                        ->whereDate('tanggal', $tanggalHariIni)
                        ->exists();
        });

        // Mahasiswa yang belum absen dan sudah mulai magang
        $belumAbsen = $mahasiswas->filter(function ($mhs) use ($tanggalHariIni) {
            $pengajuan = $mhs->pengajuan;
            if (!$pengajuan) return false;

            $sudahAbsen = Absensi::where('mahasiswa_id', $mhs->id)
                                ->whereDate('tanggal', $tanggalHariIni)
                                ->exists();

            return !$sudahAbsen &&
                Carbon::parse($pengajuan->mulai_tanggal)->lte($tanggalHariIni) &&
                Carbon::parse($pengajuan->sampai_tanggal)->gte($tanggalHariIni);
        });

        // Mahasiswa yang belum bisa absen karena magang belum dimulai
        $belumMulaiAbsen = $mahasiswas->filter(function ($mhs) use ($tanggalHariIni) {
            $pengajuan = $mhs->pengajuan;
            if (!$pengajuan || $pengajuan->status !== 'Diterima') return false;

            return Carbon::parse($pengajuan->mulai_tanggal)->gt($tanggalHariIni);
        });

        return view('menuadmin.absensi.index', [
            'tanggalHariIni' => $tanggalHariIni->format('Y-m-d'),
            'listKampus' => $listKampus,
            'sudahAbsen' => $sudahAbsen,
            'belumAbsen' => $belumAbsen,
            'belumMulaiAbsen' => $belumMulaiAbsen,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $mahasiswa = MahasiswaModel::with('pengajuan')->find($request->mahasiswa_id);
        if (!$mahasiswa || !$mahasiswa->pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }
        $pengajuan = $mahasiswa->pengajuan;


        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan');
        }

        $today = Carbon::today();

        if ($today->lt(Carbon::parse($pengajuan->mulai_tanggal))) {
            return back()->with('error', 'Mahasiswa belum mulai magang. Absensi tidak bisa dilakukan.');
        }

        // Cek apakah sudah absen hari ini
        $sudahAbsen = Absensi::where('mahasiswa_id', $request->mahasiswa_id)
                            ->whereDate('tanggal', $today)
                            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Mahasiswa sudah absen hari ini.');
        }

        Absensi::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'tanggal' => $today,
            'status' => $request->status,
            'user_id' => $mahasiswa->user_id,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Absensi berhasil disimpan.');
    }


    public function filter(Request $request){
        $tanggal = $request->input('tanggal');

        $query = Absensi::with(['mahasiswas.pengajuans']);

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $data = $query->get();

        return view('menuadmin.absensi.index', [
            'data' => $data,
            'tanggal' => $tanggal ?? ''
        ]);
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $mahasiswa = MahasiswaModel::with('pengajuan')->find($request->mahasiswa_id);
        if (!$mahasiswa || !$mahasiswa->pengajuan) {
            return response()->json(['success' => false, 'message' => 'Data pengajuan tidak ditemukan.']);
        }

        $pengajuan = $mahasiswa->pengajuan;


        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Data pengajuan tidak ditemukan.']);
        }

        // Cek apakah tanggal hari ini masuk dalam periode magang
        $mulai = Carbon::parse($pengajuan->mulai_tanggal);
        $selesai = Carbon::parse($pengajuan->sampai_tanggal);

        if ($tanggal->lt($mulai)) {
            return response()->json(['success' => false, 'message' => 'Magang belum dimulai.']);
        }

        if ($tanggal->gt($selesai)) {
            return response()->json(['success' => false, 'message' => 'Magang sudah selesai.']);
        }

        // Cek apakah sudah absen pada tanggal ini
        $sudahAbsen = Absensi::where('mahasiswa_id', $request->mahasiswa_id)
                            ->whereDate('tanggal', $tanggal)
                            ->exists();

        if ($sudahAbsen) {
            return response()->json(['success' => false, 'message' => 'Sudah absen hari ini.']);
        }

        // Simpan absensi
        Absensi::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'tanggal' => $tanggal,
            'status' => $request->status,
            'user_id' => $mahasiswa->user_id,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true]);
    }

    public function rekap(Request $request)
    {
        $kampus_id = $request->input('kampus_id');

        $query = Absensi::with('mahasiswa.kampus')
            ->whereHas('mahasiswa.pengajuan', function ($q) {
                $q->where('status', 'Diterima');
            });

        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', Carbon::parse($request->start_date)->startOfDay());
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', Carbon::parse($request->end_date)->endOfDay());
        }

        

        $absensiData = $query->get()->groupBy('mahasiswa_id');

        $mahasiswaList = MahasiswaModel::with('kampus')
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'Diterima');
            });

        if ($kampus_id) {
            $mahasiswaList->where('user_id', $kampus_id);
        }

        $mahasiswaList = $mahasiswaList->orderBy('nama_mahasiswa')->get();
        $listKampus = User::where('role', 'kampus')->get();

        return view('menuadmin.absensi.rekap', compact('absensiData', 'mahasiswaList', 'listKampus'));
    }

    
    public function exportPDF($id)
    {
        $mahasiswa = MahasiswaModel::with('kampus')->findOrFail($id);
        $absensi = Absensi::where('mahasiswa_id', $id)->get();

        $hadir = $absensi->where('status', 'hadir')->count();
        $izin  = $absensi->where('status', 'izin')->count();
        $sakit = $absensi->where('status', 'sakit')->count();
        $alfa  = $absensi->where('status', 'alfa')->count();

        $pdf = pdf::loadView('menuadmin.absensi.pdf.rekap', compact('mahasiswa', 'hadir', 'izin', 'sakit', 'alfa'));
        return $pdf->stream('rekap_absensi_'.$mahasiswa->nama_mahasiswa.'.pdf');
    }

    public function exportALLPDF(){
        $mahasiswaList = MahasiswaModel::with(['kampus', 'absensis'])
            ->whereHas('pengajuan', function($q){
                $q->where('status','Diterima');
            })
            ->get();

        // Hitung absensi per mahasiswa
        $data = $mahasiswaList->map(function($mhs){
            $pengajuan = $mhs->pengajuan;
            return [
                'nama' => $mhs->nama_mahasiswa,
                'nim' => $mhs->nim,
                'kampus' => $mhs->kampus->name ?? '-',
                'mulai_tanggal' => $pengajuan->mulai_tanggal ?? null,
                'sampai_tanggal' => $pengajuan->sampai_tanggal ?? null,
                'hadir' => $mhs->absensis->where('status', 'hadir')->count(),
                'izin' => $mhs->absensis->where('status', 'izin')->count(),
                'sakit' => $mhs->absensis->where('status', 'sakit')->count(),
                'alfa' => $mhs->absensis->where('status', 'alfa')->count(),
            ];
        });

        $pdf = PDF::loadView('menuadmin.absensi.pdf.rekap_all', ['data' => $data]);
        return $pdf->stream('rekap_semua_mahasiswa.pdf');
    }


}
