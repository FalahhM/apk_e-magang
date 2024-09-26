<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    // Menambahkan halaman dari PDF pertama
    $pageCount = $fpdi->setSourceFile($firstPdf);
    for ($i = 1; $i <= $pageCount; $i++) {
        $templateId = $fpdi->importPage($i);
        $fpdi->AddPage();
        $fpdi->useTemplate($templateId);
    }

    // Menambahkan halaman dari PDF kedua
    $pageCount = $fpdi->setSourceFile($secondPdfPath);
    for ($i = 1; $i <= $pageCount; $i++) {
        $templateId = $fpdi->importPage($i);
        $fpdi->AddPage();
        $fpdi->useTemplate($templateId);
    }

    // Mengeluarkan PDF akhir
    return $fpdi->Output('Surat_Pengajuan.pdf', 'I');
}

}
