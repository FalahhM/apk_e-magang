<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use setasign\Fpdi\Fpdi;


class AdminController extends Controller
{
    public function index(){
        return view('admin');
    }

    public function tampilPengajuan(){
        $data_pengajuan = PengajuanModel::with('user')->get();
        return view('menuadmin.pengajuanmagang', compact('data_pengajuan'));
    }

    public function detailpengajuan($id){
        $pengajuan = PengajuanModel::with(['user.contactPerson', 'mahasiswas'])->findOrFail($id);
        return view('menuadmin.detailpengajuan', compact('pengajuan'));
    }

    public function prosesPengajuan(Request $request, $id){
        $request->validate([
            'status' => 'required|string',
        ]);

        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Sedang Di Proses'; 
        $pengajuan->save();

        // Flash message
        session()->flash('message', 'Pengajuan berhasil diproses.');

        return redirect()->route('detailpengajuan', ['id' => $id]);
    }

    public function bulanRomawi($bulan){
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        return $romawi[$bulan] ?? '';
    }

    public function cetakProsesPDF($id) {
        $pengajuan = PengajuanModel::with(['user.contactPerson', 'mahasiswas'])->findOrFail($id);
        
        $pengajuan->cetak_timestamp = now();
        $pengajuan->save();

        $jumlahOrang = count($pengajuan->mahasiswas);
        $bulanRomawi = $this->bulanRomawi(now()->month);

        $html = view('menuadmin.pdf.surat', compact('pengajuan','jumlahOrang','bulanRomawi'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $firstPdf = 'first_pdf.pdf';
        $mpdf->Output($firstPdf, 'F');

        $secondPdfPath = public_path('storage/' . $pengajuan->dokumen_file);
        if (!file_exists($secondPdfPath)) {
            dd("File tidak ditemukan: " . $secondPdfPath);
        }

        $fpdi = new Fpdi();

        $pageCount = $fpdi->setSourceFile($firstPdf);
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $fpdi->importPage($i);
            $fpdi->AddPage();
            $fpdi->useTemplate($templateId);
        }

        $pageCount = $fpdi->setSourceFile($secondPdfPath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $fpdi->importPage($i);
            $fpdi->AddPage();
            $fpdi->useTemplate($templateId);
        }

        return $fpdi->Output('Surat_Pengajuan.pdf', 'I');
    }
  
    public function terimaPengajuan(Request $request, $id){
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Diterima';
        $pengajuan->save();

        $pengajuan->cetakTerima_timestamp = now();
        $pengajuan->save();

        $jumlahOrang = count($pengajuan->mahasiswas);
        $bulanRomawi = $this->bulanRomawi(now()->month);

        $noSuratTerima = PengajuanModel::max('noSuratTerima');

        if ($noSuratTerima){
            $newNoSuratTerima = $noSuratTerima + 1;
        }else {
            $newNoSuratTerima = 1;
        }

        $pengajuan->noSuratTerima = $newNoSuratTerima;
        $pengajuan->save();

        $qrData = "Nomor Surat: {$pengajuan->noSuratTerima}\nNama Kabag: {$pengajuan->nama_kabag}\nTanggal Cetak: {$pengajuan->cetakTerima_timestamp}";
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->encoding(new Encoding('UTF-8'))
            ->size(150)
            ->margin(10)
            ->build()->getDataUri();

        // Mendefinisikan path untuk menyimpan QR Code
        $qrCodeUri = $result;

        $html = view('menuadmin.pdf.suratTerima', compact('pengajuan','jumlahOrang','bulanRomawi','qrCodeUri'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html); 
        $fileName = 'Surat_Balasan_Terima_' . $pengajuan->id . '.pdf';
        $filePath = public_path('storage/' . $fileName);
        $mpdf->Output($filePath,'F', ['qrCodeUri' => $qrCodeUri]);

        $pengajuan->balasanTerima = $fileName;
        $pengajuan->save();
        // // kirim email

        session()->flash('message', 'Pengajuan telah diterima.');

        return redirect()->route('detailpengajuan', ['id' => $id]);
    }

    public function lihatSuratTerima($id) {
         // Mencari pengajuan berdasarkan ID
        $pengajuan = PengajuanModel::findOrFail($id);
        
        // Mendapatkan path file
        $filePath = public_path('storage/' . $pengajuan->balasanTerima);


        // Mengecek apakah file ada
        if (!file_exists($filePath)) {
            // Jika file tidak ditemukan, kembalikan 404
            return abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath);
    }
}
