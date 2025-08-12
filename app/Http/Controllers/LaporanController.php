<?php

namespace App\Http\Controllers;

use App\Models\LaporanMagang;
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
        $pengajuanSelesai = PengajuanModel::whereDate('sampai_tanggal', '<=', now())
            ->with(['mahasiswas', 'laporanMagang'])
            ->get();

        $kampusList = MahasiswaModel::with('user')
            ->get()
            ->pluck('user.name')
            ->unique()
            ->sort()
            ->values();

        return view('menuadmin.laporan.index', compact('pengajuanSelesai', 'kampusList'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
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

        $laporan = LaporanMagang::create([
            'pengajuan_id' => $request->pengajuan_id,
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

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan. Klik "Kirim Sertifikat" untuk mengirim ke email mahasiswa.');
    }

    public function lihatSertifikat($id)
    {
        $laporan = LaporanMagang::findOrFail($id);
        $pengajuan = $laporan->pengajuan()->with('mahasiswas')->first();
        $mahasiswa = $pengajuan->mahasiswas->first();

        $nilaiRataRata = round($laporan->total_nilai);
        $kategori = $laporan->predikat;

        $pdf = Pdf::loadView('menuadmin.pdf.sertifikat', compact('laporan', 'pengajuan', 'mahasiswa', 'nilaiRataRata', 'kategori'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_' . $mahasiswa->nama_mahasiswa . '.pdf');
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanMagang::findOrFail($id);

        $laporan->update([
            'integritas' => $request->integritas,
            'ketepatan_waktu' => $request->ketepatan_waktu,
            'keahlian' => $request->keahlian,
            'teamwork' => $request->teamwork,
            'komunikasi' => $request->komunikasi,
            'teknologi' => $request->teknologi,
            'pengembangan_diri' => $request->pengembangan_diri,
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil diperbarui.');
    }

    
    public function kirim($id)
    {
        $laporan = LaporanMagang::findOrFail($id);
        
        $pengajuan = PengajuanModel::with('mahasiswas')->findOrFail($laporan->pengajuan_id);
        $mahasiswa = $pengajuan->mahasiswas->first();

        $nilaiRataRata = round($laporan->total_nilai);
        $kategori = $laporan->predikat;

        $pdf = Pdf::loadView('menuadmin.pdf.sertifikat', compact('laporan', 'pengajuan', 'mahasiswa', 'nilaiRataRata', 'kategori'))
            ->setPaper('a4', 'landscape');

        $filename = 'sertifikat_' . str_replace(' ', '_', strtolower($mahasiswa->nama_mahasiswa)) . '.pdf';

        $folderPath = storage_path('app/public/sertifikat');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $path = $folderPath . '/' . $filename;
        $pdf->save($path);

        if ($mahasiswa->email) {
            Mail::to($mahasiswa->email)->send(new SertifikatMagangMail($mahasiswa->nama_mahasiswa, $path));
        }

        return back()->with('success', 'Sertifikat berhasil dikirim ke email mahasiswa.');
    }

    private function kategoriNilai($nilai)
    {
        if ($nilai < 70) return 'Kurang Memuaskan';
        if ($nilai <= 85) return 'Memuaskan';
        return 'Sangat Memuaskan';
    }
}
