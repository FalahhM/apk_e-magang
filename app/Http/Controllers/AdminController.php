<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use setasign\Fpdi\Fpdi;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanDiterimaMail;
use App\Mail\PengajuanDitolakMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // ✅ DASHBOARD
    public function index()
    {
        // Statistik pengajuan
        $total_pengajuan     = PengajuanModel::count();
        $pengajuan_diterima  = PengajuanModel::where('status', 'Diterima')->count();
        $pengajuan_ditolak   = PengajuanModel::where('status', 'Ditolak')->count();
        $pengajuan_diproses  = PengajuanModel::where('status', 'Sedang Di Proses')->count();

        // Statistik absensi
        $totalHadir = Absensi::where('status', 'Hadir')->count();
        $totalIzin  = Absensi::where('status', 'Izin')->count();
        $totalSakit = Absensi::where('status', 'Sakit')->count();
        $totalAlfa  = Absensi::where('status', 'Alfa')->count();

        return view('admin', compact(
            'total_pengajuan',
            'pengajuan_diterima',
            'pengajuan_ditolak',
            'pengajuan_diproses',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa'
        ));
    }


    // ✅ TAMPIL PENGAJUAN
    public function tampilPengajuan(Request $request)
    {
        $query = PengajuanModel::with('user');

        if ($request->filled('no_surat')) {
            $query->where('no_surat', 'like', '%' . $request->no_surat . '%');
        }

        if ($request->filled('nama_kampus')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_kampus . '%');
            });
        }

        $data_pengajuan = $query->get();

        return view('menuadmin.pengajuanmagang', compact('data_pengajuan'));
    }

    public function detailpengajuan($id)
    {
        $pengajuan = PengajuanModel::with(['user.contactPerson', 'mahasiswas'])->findOrFail($id);
        return view('menuadmin.detailpengajuan', compact('pengajuan'));
    }

    public function prosesPengajuan(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Sedang Di Proses';
        $pengajuan->save();

        session()->flash('message', 'Pengajuan berhasil diproses.');
        return redirect()->route('detailpengajuan', ['id' => $id]);
    }

    // ✅ ROMAWI UTILITY
    public function bulanRomawi($bulan)
    {
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                   7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romawi[$bulan] ?? '';
    }

    // ✅ CETAK PROSES
    public function cetakProsesPDF($id)
    {
        $pengajuan = PengajuanModel::with(['user.contactPerson', 'mahasiswas'])->findOrFail($id);
        $pengajuan->cetak_timestamp = now();
        $pengajuan->save();

        $jumlahOrang = $pengajuan->mahasiswas->count();
        $bulanRomawi = $this->bulanRomawi(now()->month);

        $html = view('menuadmin.pdf.surat', compact('pengajuan','jumlahOrang','bulanRomawi'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $firstPdf = 'first_pdf.pdf';
        $mpdf->Output($firstPdf, 'F');

        $secondPdfPath = public_path('storage/' . $pengajuan->dokumen_file);
        if (!file_exists($secondPdfPath)) {
            abort(404, "File tidak ditemukan: " . $secondPdfPath);
        }

        $fpdi = new Fpdi();
        $pageCount = $fpdi->setSourceFile($firstPdf);
        for ($i = 1; $i <= $pageCount; $i++) {
            $fpdi->AddPage();
            $fpdi->useTemplate($fpdi->importPage($i));
        }

        $pageCount = $fpdi->setSourceFile($secondPdfPath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $fpdi->AddPage();
            $fpdi->useTemplate($fpdi->importPage($i));
        }

        return $fpdi->Output('Surat_Pengajuan.pdf', 'I');
    }

    // ✅ TERIMA PENGAJUAN
    public function terimaPengajuan(Request $request, $id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Diterima';
        $pengajuan->cetakTerima_timestamp = now();

        $jumlahOrang = $pengajuan->mahasiswas->count();
        $bulanRomawi = $this->bulanRomawi(now()->month);
        $newNoSurat = PengajuanModel::max('noSuratTerima') + 1 ?? 1;

        $pengajuan->noSuratTerima = $newNoSurat;
        $pengajuan->save();

        foreach ($pengajuan->mahasiswas as $mahasiswa) {
            if($mahasiswa->dospem && !empty($mahasiswa->dospem->email)) {
                
                // Cari user dospem, kalau belum ada buat baru
                $userDospem = User::where('email', $mahasiswa->dospem->email)->first();
                if(!$userDospem){
                    $userDospem = User::create([
                        'name' => $mahasiswa->dospem->nama_dospem ?? '-',
                        'alamat' => '-', 
                        'email' => $mahasiswa->dospem->email ?? '-',
                        'no_telp' => '-',
                        'password' => Hash::make('123'),
                        'role' => 'dospem',
                        'email_verified_at' => now(),
                    ]);
                }

                // Update user_id di tabel dospems
                $mahasiswa->dospem->user_id = $userDospem->id;
                $mahasiswa->dospem->pengajuan_id = $pengajuan->id;
                $mahasiswa->dospem->save();
            }
        }


        foreach ($pengajuan->mahasiswas as $mahasiswa) {
            $user = User::where('email', $mahasiswa->email)->first();
            if(!$user){
                $user = User::create([
                    'name' => $mahasiswa->nama_mahasiswa,
                    'alamat' => '-', 
                    'email' => $mahasiswa->email,
                    'no_telp' => '-',
                    'password' => Hash::make('123'),
                    'role' => 'mahasiswa',
                    'email_verified_at' => now(),
                ]);
                $mahasiswa->user_id = $user->id;
                $mahasiswa->save();
            }
        }

        $qrData = "Nomor Surat: {$newNoSurat}\nNama Kabag: {$pengajuan->nama_kabag}\nTanggal Cetak: {$pengajuan->cetakTerima_timestamp}";
        $qrCodeUri = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->encoding(new Encoding('UTF-8'))
            ->size(150)
            ->margin(10)
            ->build()->getDataUri();

        $html = view('menuadmin.pdf.suratTerima', compact('pengajuan','jumlahOrang','bulanRomawi','qrCodeUri'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $fileName = 'Surat_Balasan_Terima_' . $pengajuan->id . '.pdf';
        $mpdf->Output(public_path('storage/' . $fileName), 'F');

        $pengajuan->balasanTerima = $fileName;
        $pengajuan->save();

        Mail::to($pengajuan->user->email)->send(new PengajuanDiterimaMail($pengajuan, $bulanRomawi));
        session()->flash('message', 'Pengajuan telah diterima.');
        return redirect()->route('detailpengajuan', ['id' => $id]);
    }

    public function lihatSuratTerima($id)
    {
        $path = public_path('storage/' . PengajuanModel::findOrFail($id)->balasanTerima);
        abort_unless(file_exists($path), 404);
        return response()->file($path);
    }

    // ✅ TOLAK PENGAJUAN
    public function tolakPengajuan(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);

        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Ditolak';
        $pengajuan->alasanTolak = $request->alasan;
        $pengajuan->cetakTolak_timestamp = now();

        $jumlahOrang = $pengajuan->mahasiswas->count();
        $bulanRomawi = $this->bulanRomawi(now()->month);
        $newNoSurat = PengajuanModel::max('noSuratTolak') + 1 ?? 1;

        $pengajuan->noSuratTolak = $newNoSurat;
        $pengajuan->save();

        $qrData = "Nomor Surat: {$newNoSurat}\nNama Kabag: {$pengajuan->nama_kabag}\nTanggal Cetak: {$pengajuan->cetakTolak_timestamp}";
        $qrCodeUri = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->encoding(new Encoding('UTF-8'))
            ->size(150)
            ->margin(10)
            ->build()->getDataUri();

        $html = view('menuadmin.pdf.suratTolak', compact('pengajuan','jumlahOrang','bulanRomawi','qrCodeUri'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $fileName = 'Surat_Balasan_Tolak_' . $pengajuan->id . '.pdf';
        $mpdf->Output(public_path('storage/' . $fileName), 'F');

        $pengajuan->balasanTolak = $fileName;
        $pengajuan->save();

        Mail::to($pengajuan->user->email)->send(new PengajuanDitolakMail($pengajuan, $bulanRomawi));
        session()->flash('message', 'Pengajuan telah ditolak.');
        return redirect()->route('detailpengajuan', ['id' => $id]);
    }

    public function lihatSuratTolak($id)
    {
        $path = public_path('storage/' . PengajuanModel::findOrFail($id)->balasanTolak);
        abort_unless(file_exists($path), 404);
        return response()->file($path);
    }
}
