<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LaporanMagang;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PgSql\Lob;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa   = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa->id;

        // Ambil pengajuan yang diterima
        $pengajuan = $mahasiswa->pengajuan()
            ->where('status', 'Diterima')
            ->latest('mulai_tanggal') // ambil yang terbaru
            ->first();

        // Ambil periode magang dari pengajuan
        $periodeMulai   = $pengajuan ? $pengajuan->mulai_tanggal   : null;
        $periodeSelesai = $pengajuan ? $pengajuan->sampai_tanggal : null;

        // Hitung absensi
        $totalHadir = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'hadir')
            ->count();

        $totalIzin = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'izin')
            ->count();

        $totalSakit = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'sakit')
            ->count();

        // Hitung laporan
        $totalLaporan = LaporanMagang::where('mahasiswa_id', $mahasiswaId)->count();

        // Ambil nama pembimbing
        $pembimbing = LaporanMagang::where('mahasiswa_id', $mahasiswaId)
            ->value('nama_pembimbing_lapangan') ?? '-';

        return view('mahasiswa.dashboard', compact(
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalLaporan',
            'pembimbing',
            'periodeMulai',
            'periodeSelesai'
        ));
    }


    public function profil()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswa->load('dospem');

        return view('mahasiswa.profil', compact('mahasiswa'));
    }


    // =========================
    // ABSENSI
    // =========================

    public function absensiForm(Request $request)
    {
        Carbon::setLocale('id');
        $today = Carbon::today()->toDateString();
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa->id;

        // Cek sudah absen hari ini
        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', $today)
            ->exists();

        // --- TAMBAHAN: ambil pengajuan yang Diterima untuk periode magang ---
        $pengajuan = $mahasiswa->pengajuan()
            ->where('status', 'Diterima')
            ->first(); // bisa null kalau belum ada yang diterima

        // Query riwayat absensi
        $query = Absensi::where('mahasiswa_id', $mahasiswaId)->orderBy('tanggal', 'desc');
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        $riwayatAbsensi = $query->get();

        return view('mahasiswa.absensi', [
            'alreadyAbsent'  => $alreadyAbsent,
            'today'          => $today,
            'riwayatAbsensi' => $riwayatAbsensi,
            'filterTanggal'  => $request->tanggal,
            'pengajuan'      => $pengajuan,   // <-- KIRIM KE VIEW
        ]);
    }


    public function absensiStore(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $today = Carbon::today()->toDateString();
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa->id;

        // --- CEK PERIODE MAGANG ---
        $pengajuan = $mahasiswa->pengajuan()->where('status','Diterima')->first();
        if (!$pengajuan) {
            return back()->with('error', 'Kamu belum memiliki pengajuan magang yang diterima.');
        }

        // Cek jika sebelum periode mulai
        if (Carbon::parse($today)->lt(Carbon::parse($pengajuan->mulai_tanggal))) {
            return back()->with('error', 'Periode magang belum dimulai, kamu belum bisa absen.');
        }

        // Cek jika setelah periode selesai
        if (Carbon::parse($today)->gt(Carbon::parse($pengajuan->sampai_tanggal))) {
            return back()->with('error', 'Periode magang sudah habis.');
        }

        // cek weekend
        if (Carbon::parse($today)->isWeekend()){
            return back()->with('error', 'Absensi hanya bisa dilakukan pada hari kerja (Senin-Jumat).');
        }

        // Sudah absen?
        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', $today)
            ->exists();
        if ($alreadyAbsent) {
            return back()->with('error', 'Kamu sudah absen hari ini.');
        }

        $keterangan = $request->keterangan;
        if ($request->status === 'hadir' && empty($keterangan)) {
            $keterangan = 'Hadir melaksanakan magang';
        }

        Absensi::create([
            'mahasiswa_id' => $mahasiswaId,
            'user_id'      => Auth::id(),
            'tanggal'      => $today,
            'status'       => $request->status,
            'keterangan'   => $keterangan
        ]);

        return redirect()->route('mahasiswa.absensi')->with('success', 'Absensi berhasil disimpan.');
    }




    // =========================
    // LAPORAN KEGIATAN MAGANG
    // =========================

    public function laporanIndex()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa->id;

        $laporans = LaporanMagang::where('mahasiswa_id', $mahasiswaId)->get();

        // cek periode magang dari relasi pengajuan yang diterima
        $pengajuan = $mahasiswa->pengajuan()->where('status', 'Diterima')->first();

        $periodeHabis = false;
        if ($pengajuan) {
            $periodeHabis = Carbon::today()->gt(Carbon::parse($pengajuan->sampai_tanggal));
        }

        // cek sudah absen hari ini
        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        return view('mahasiswa.laporan.index', compact('laporans', 'periodeHabis', 'sudahAbsen'));
    }


    public function simpanPembimbing(Request $request)
    {
        $request->validate([
            'nama_pembimbing_lapangan' => 'required|string|max:255'
        ]);

        LaporanMagang::where('mahasiswa_id', Auth::user()->mahasiswa->id)
            ->update(['nama_pembimbing_lapangan' => $request->nama_pembimbing_lapangan]);

        return redirect()->back()->with('success', 'Nama pembimbing berhasil disimpan.');
    }

    public function laporanCreate()
    {
        $mahasiswaId = Auth::user()->mahasiswa->id;
        $tanggalHariIni = Carbon::today();

        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', $tanggalHariIni)
            ->exists();

        if(!$sudahAbsen){
            return redirect()->route('mahasiswa.laporan.index')
                ->with('error', 'Isi absen hari ini terlebih dahulu sebelum menambah laporan');
        }

        return view('mahasiswa.laporan.create');
    }

    public function laporanStore(Request $request)
    {
        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'keterangan'       => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_dokumentasi')) {
            $fotoPath = $request->file('foto_dokumentasi')->store('foto_kegiatan', 'public');
        }

        LaporanMagang::create([
            'mahasiswa_id'     => Auth::user()->mahasiswa->id,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'keterangan'       => $request->keterangan,
            'foto_dokumentasi' => $fotoPath,
        ]);

        return redirect()->route('mahasiswa.laporan.index')->with('success', 'Laporan berhasil ditambahkan');
    }

    public function laporanCetakSemua()
    {
        Carbon::setLocale('id');

        $laporans = LaporanMagang::where('mahasiswa_id', Auth::user()->mahasiswa->id)
            ->orderBy('tanggal_kegiatan', 'asc')
            ->get();

        if ($laporans->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada laporan untuk dicetak.');
        }

        $firstLaporan = $laporans->first();
        $mahasiswa = MahasiswaModel::find($firstLaporan->mahasiswa_id);
        $pembimbingNama = $firstLaporan->nama_pembimbing_lapangan ?? '________________';
        $pembimbing = (object) ['nama' => $pembimbingNama];

        $pdf = Pdf::loadView('mahasiswa.laporan.cetak_semua', [
            'laporans'   => $laporans,
            'mahasiswa'  => $mahasiswa,
            'pembimbing' => $pembimbing
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_kegiatan_magang.pdf');
    }

    public function laporanEdit($id)
    {
        $laporan = LaporanMagang::where('mahasiswa_id', Auth::user()->mahasiswa->id)->findOrFail($id);
        return view('mahasiswa.laporan.edit', compact('laporan'));
    }

    public function laporanUpdate(Request $request, $id)
    {
        $laporan = LaporanMagang::where('mahasiswa_id', Auth::user()->mahasiswa->id)->findOrFail($id);

        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'keterangan'       => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('tanggal_kegiatan', 'keterangan');

        if ($request->hasFile('foto_dokumentasi')) {
            $data['foto_dokumentasi'] = $request->file('foto_dokumentasi')->store('laporan', 'public');
        }

        $laporan->update($data);

        return redirect()->route('mahasiswa.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function laporanDestroy($id)
    {
        $laporan = LaporanMagang::where('mahasiswa_id', Auth::user()->mahasiswa->id)->findOrFail($id);
        $laporan->delete();

        return redirect()->route('mahasiswa.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
