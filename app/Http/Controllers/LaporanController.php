<?php

namespace App\Http\Controllers;

use App\Models\PenilaianMagang;
use App\Models\PengajuanModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SertifikatMagangMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {   
        $pengajuan = PengajuanModel::with(['user'])->get();
        $mahasiswaList = MahasiswaModel::with(['user', 'absensis', 'penilaianMagang', 'semuaLaporan'])
            ->when(request('nama'), function ($query) {
                $query->where('nama_mahasiswa', 'like', '%' . request('nama') . '%');
            })
            ->when(request('nim'), function ($query) {
                $query->where('nim', 'like', '%' . request('nim') . '%');
            })
            ->when(request('universitas'), function ($query) {
                $query->whereHas('pengajuan.user', function ($q) {
                    $q->where('name', 'like', '%' . request('universitas') . '%');
                });
            })
            ->get();

        $mahasiswaList->each(function($mahasiswa) {
            $mahasiswa->laporanGrouped = $mahasiswa->semuaLaporan
                ->groupBy(function($laporan) {
                    return $laporan->tanggal_kegiatan; 
                });
        });

        $kampusList = MahasiswaModel::with('pengajuan.user')
            ->get()
            ->pluck('pengajuan.user.name')
            ->unique()
            ->sort()
            ->values();

        return view('menuadmin.laporan.index', [
            'pengajuanSelesai' => $mahasiswaList,
            'kampusList' => $kampusList
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'integritas' => 'required|numeric|min:0|max:100',
            'ketepatan_waktu' => 'required|numeric|min:0|max:100',
            'keahlian' => 'required|numeric|min:0|max:100',
            'teamwork' => 'required|numeric|min:0|max:100',
            'komunikasi' => 'required|numeric|min:0|max:100',
            'teknologi' => 'required|numeric|min:0|max:100',
            'pengembangan_diri' => 'required|numeric|min:0|max:100',
        ]);

        $total = (
            $request->integritas +
            $request->ketepatan_waktu +
            $request->keahlian +
            $request->teamwork +
            $request->komunikasi +
            $request->teknologi +
            $request->pengembangan_diri
        ) / 7;

        $predikat = $this->kategoriNilai($total);

        PenilaianMagang::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'integritas' => $request->integritas,
            'ketepatan_waktu' => $request->ketepatan_waktu,
            'keahlian' => $request->keahlian,
            'teamwork' => $request->teamwork,
            'komunikasi' => $request->komunikasi,
            'teknologi' => $request->teknologi,
            'pengembangan_diri' => $request->pengembangan_diri,
            'total_nilai' => $total,
            'predikat' => $predikat,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }


    public function lihatSertifikat($id)
    {
        $penilaian = PenilaianMagang::findOrFail($id);
        $mahasiswa = $penilaian->mahasiswa;
        $pengajuan = $mahasiswa->pengajuan;

        $nilaiRataRata = round($penilaian->total_nilai);
        $kategori = $penilaian->predikat;

        $pdf = Pdf::loadView('menuadmin.pdf.sertifikat', compact('penilaian', 'mahasiswa', 'nilaiRataRata', 'kategori', 'pengajuan'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_' . $mahasiswa->nama_mahasiswa . '.pdf');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'integritas' => 'required|numeric|min:0|max:100',
            'ketepatan_waktu' => 'required|numeric|min:0|max:100',
            'keahlian' => 'required|numeric|min:0|max:100',
            'teamwork' => 'required|numeric|min:0|max:100',
            'komunikasi' => 'required|numeric|min:0|max:100',
            'teknologi' => 'required|numeric|min:0|max:100',
            'pengembangan_diri' => 'required|numeric|min:0|max:100',
        ]);

        $penilaian = PenilaianMagang::findOrFail($id);

        if ($penilaian->jumlah_edit >= 2) {
            return redirect()->back()->with('error', 'Nilai sudah pernah diedit 2 kali, tidak bisa diedit lagi.');
        }

        // Hitung ulang total nilai
        $total = (
            $request->integritas +
            $request->ketepatan_waktu +
            $request->keahlian +
            $request->teamwork +
            $request->komunikasi +
            $request->teknologi +
            $request->pengembangan_diri
        ) / 7;

        $predikat = $this->kategoriNilai($total);

        $penilaian->update([
            'integritas' => $request->integritas,
            'ketepatan_waktu' => $request->ketepatan_waktu,
            'keahlian' => $request->keahlian,
            'teamwork' => $request->teamwork,
            'komunikasi' => $request->komunikasi,
            'teknologi' => $request->teknologi,
            'pengembangan_diri' => $request->pengembangan_diri,
            'total_nilai' => $total,
            'predikat' => $predikat,
        ]);

        $penilaian->increment('jumlah_edit');

        return redirect()->back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function kirimSertifikat($id)
    {
        $penilaian = PenilaianMagang::findOrFail($id);
        $mahasiswa = $penilaian->mahasiswa;

        $pengajuan = $mahasiswa->pengajuan;

        $penilaian->increment('jumlah_kirim');
        $penilaian->terakhir_kirim_at = now();
        $penilaian->save();

        $nilaiRataRata = round($penilaian->total_nilai);
        $kategori = $penilaian->predikat;

        $pdf = Pdf::loadView('menuadmin.pdf.sertifikat', compact('penilaian', 'mahasiswa', 'nilaiRataRata', 'kategori','pengajuan'))
            ->setPaper('a4', 'landscape');

        $filename = 'sertifikat_' . str_replace(' ', '_', strtolower($mahasiswa->nama_mahasiswa)) . '.pdf';

        $folderPath = storage_path('app/public/sertifikat');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $path = $folderPath . '/' . $filename;
        $pdf->save($path);

        if ($mahasiswa->email) {
            try {
                Mail::to($mahasiswa->email)->send(new SertifikatMagangMail($mahasiswa->nama_mahasiswa, $path));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sertifikat berhasil dikirim ke email ' . $mahasiswa->email
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim email: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'status' => 'warning',
            'message' => 'Sertifikat dibuat tapi email mahasiswa tidak tersedia'
        ]);
    }


    public function destroy($id)
    {
        try {
            $penilaian = PenilaianMagang::findOrFail($id);
            $penilaian->delete();

            return redirect()->back()->with('success', 'Penilaian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus penilaian: ' . $e->getMessage());
        }
    }

    private function kategoriNilai($nilai)
    {
        if ($nilai < 70) return 'Kurang Memuaskan';
        if ($nilai <= 85) return 'Memuaskan';
        return 'Sangat Memuaskan';
    }
}