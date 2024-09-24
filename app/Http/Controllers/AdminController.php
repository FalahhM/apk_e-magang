<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Validasi data jika diperlukan
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
}
