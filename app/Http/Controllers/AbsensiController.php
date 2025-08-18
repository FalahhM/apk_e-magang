<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Absensi;
use App\Models\User;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggalHariIni = Carbon::today();
        $search = $request->search;
        $kampusId = $request->kampus_id;

        // Ambil daftar kampus untuk filter dropdown berdasarkan pengajuan diterima
        $listKampus = User::where('role', 'kampus')
            ->whereIn('id', function($query){
                $query->select('user_id')
                    ->from('pengajuans')
                    ->where('status', 'Diterima');
            })
            ->orderBy('name')
            ->get();

        // Ambil semua mahasiswa yang punya pengajuan magang diterima
        $mahasiswaQuery = MahasiswaModel::with(['pengajuan', 'user'])
            ->whereHas('pengajuan', function ($q) {
                $q->whereNotNull('mulai_tanggal')
                  ->whereNotNull('sampai_tanggal')
                  ->where('status', 'Diterima');
            });

        if ($kampusId) {
            $mahasiswaQuery->whereHas('pengajuan', function($q) use ($kampusId){
                $q->where('user_id', $kampusId);
            });
        }

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

        // Mahasiswa yang belum absen tapi sudah mulai magang
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
            'mahasiswaList' => $mahasiswas
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

        $today = Carbon::today();

        if($today->isWeekend()){
            return back()->with('error', 'Absensi hanya bisa dilakukan pada hari kerja (Senin-Jumat).');
        }

        if ($today->lt(Carbon::parse($pengajuan->mulai_tanggal))) {
            return back()->with('error', 'Mahasiswa belum mulai magang. Absensi tidak bisa dilakukan.');
        }

        $sudahAbsen = Absensi::where('mahasiswa_id', $request->mahasiswa_id)
                            ->whereDate('tanggal', $today)
                            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Mahasiswa sudah absen hari ini.');
        }

        $status = strtolower($request->status);

        Absensi::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'tanggal' => $today,
            'status' => $status,
            'user_id' => $mahasiswa->user_id,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

        public function edit($id)
    {
        $absensi = Absensi::with('mahasiswa.pengajuan.user')->findOrFail($id);
        return view('menuadmin.absensi.edit', compact('absensi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => strtolower($request->status),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate.');
    }


    public function rekap(Request $request)
    {
        $kampus_id = $request->input('kampus_id');

        $query = Absensi::with('mahasiswa.pengajuan.user')
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

        $mahasiswaList = MahasiswaModel::with('pengajuan.user')
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'Diterima');
            });

        if ($kampus_id) {
            $mahasiswaList->where('user_id', $kampus_id);
        }

        $mahasiswaList = $mahasiswaList->orderBy('nama_mahasiswa')->get();

        $listKampus = User::where('role', 'kampus')
            ->whereIn('id', function($query){
                $query->select('user_id')
                    ->from('pengajuans')
                    ->where('status', 'Diterima');
            })
            ->orderBy('name')
            ->get();

        return view('menuadmin.absensi.rekap', compact('absensiData', 'mahasiswaList', 'listKampus'));
    }

    public function exportALLPDF(Request $request)
    {
        $mahasiswaList = MahasiswaModel::with(['pengajuan.user', 'absensis'])
            ->whereHas('pengajuan', function($q){
                $q->where('status','Diterima');
            })
            ->orderBy('nama_mahasiswa')
            ->get();

        $groupedByKampus = [];

        foreach ($mahasiswaList as $mhs) {
            $kampusName = $mhs->pengajuan->user->name ?? 'Tidak Diketahui';
            
            foreach ($mhs->absensis as $absen) {
                $bulan = Carbon::parse($absen->tanggal)->format('Y-m');
                $tanggal = Carbon::parse($absen->tanggal)->toDateString();

                $groupedByKampus[$kampusName][$bulan][$mhs->id]['mahasiswa'] = $mhs;
                $groupedByKampus[$kampusName][$bulan][$mhs->id]['absensi'][$tanggal] = strtoupper(substr($absen->status,0,1));
            }
        }

        $pdf = Pdf::loadView('menuadmin.absensi.pdf.rekap_all', [
            'groupedByKampus' => $groupedByKampus
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('rekap_semua_mahasiswa_per_kampus.pdf');
    }

    public function exportPDF($mahasiswa_id)
    {
        $mahasiswa = MahasiswaModel::with(['pengajuan.user', 'absensis'])
            ->whereHas('pengajuan', function($q){
                $q->where('status', 'Diterima');
            })
            ->findOrFail($mahasiswa_id);

        // Hitung jumlah status jika masih mau disertakan
        $hadir = $mahasiswa->absensis->where('status', 'hadir')->count();
        $izin  = $mahasiswa->absensis->where('status', 'izin')->count();
        $sakit = $mahasiswa->absensis->where('status', 'sakit')->count();
        $alfa  = $mahasiswa->absensis->where('status', 'alfa')->count();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('menuadmin.absensi.pdf.rekap', [
            'mahasiswa' => $mahasiswa,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alfa' => $alfa
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('rekap_absensi_'.$mahasiswa->nim.'.pdf');
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
                'keterangan' => 'nullable|string|max:255',
                'tanggal' => 'required|date'
            ]);

            $tanggal = Carbon::parse($request->tanggal)->toDateString();
            $mahasiswa = MahasiswaModel::with('pengajuan')->findOrFail($request->mahasiswa_id);

            if (!$mahasiswa->pengajuan || $mahasiswa->pengajuan->status !== 'Diterima') {
                return response()->json(['success' => false, 'message' => 'Pengajuan belum diterima.']);
            }

            if (Carbon::parse($tanggal)->isWeekend()) {
                return response()->json(['success' => false, 'message' => 'Absensi hanya tersedia di hari kerja.']);
            }

            $absenSudahAda = Absensi::where('mahasiswa_id', $request->mahasiswa_id)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if ($absenSudahAda) {
                return response()->json(['success' => false, 'message' => 'Sudah absen hari ini.']);
            }

            $absensi = Absensi::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'tanggal'      => $tanggal,
                'status'       => strtolower($request->status),
                'user_id'      => $mahasiswa->user_id,
                'keterangan'   => $request->keterangan ?? 'Hadir melaksanakan magang',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil disimpan.',
                'id'      => $absensi->id // kirim id supaya bisa dipakai untuk tombol edit
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

}
