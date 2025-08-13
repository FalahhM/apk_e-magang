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

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dashboard');
    }

    // =========================
    // ABSENSI
    // =========================

    public function absensiForm(Request $request)
    {
        Carbon::setLocale('id');
        $today = Carbon::today()->toDateString();
        $mahasiswaId = Auth::user()->mahasiswa->id;

        // Cek apakah sudah absen hari ini
        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', $today)
            ->exists();

        // Query riwayat absensi
        $query = Absensi::where('mahasiswa_id', $mahasiswaId)->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $riwayatAbsensi = $query->get();

        return view('mahasiswa.absensi', [
            'alreadyAbsent' => $alreadyAbsent,
            'today' => $today,
            'riwayatAbsensi' => $riwayatAbsensi,
            'filterTanggal' => $request->tanggal
        ]);
    }

    public function absensiStore(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $today = Carbon::today()->toDateString();
        $mahasiswaId = Auth::user()->mahasiswa->id;

        $alreadyAbsent = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($alreadyAbsent) {
            return redirect()->back()->with('error', 'Kamu sudah absen hari ini.');
        }

        $keterangan = $request->keterangan;
        if ($request->status === 'hadir' && empty($keterangan)) {
            $keterangan = 'Hadir melaksanakan magang';
        }

        Absensi::create([
            'mahasiswa_id' => $mahasiswaId,
            'user_id'      => Auth::id(), // ini boleh tetap Auth::id() karena user_id merujuk ke tabel users
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
        $mahasiswaId = Auth::user()->mahasiswa->id;
        $laporans = LaporanMagang::where('mahasiswa_id', $mahasiswaId)->get();
        return view('mahasiswa.laporan.index', compact('laporans'));
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
