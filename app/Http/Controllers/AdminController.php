<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    function index(){
        return view ('admin');
    }
    
    public function pengajuan(){
        return $this->tampilPengajuan();
    }

    public function tampilPengajuan(){
        $data_pengajuan = PengajuanModel::with('user')->get();
        return view('menuadmin.pengajuanmagang',compact('data_pengajuan'));
    }

    public function detailpengajuan($id){
        $pengajuan = PengajuanModel::with('user.contactPerson','mahasiswas')->findOrFail($id);
        return view('menuadmin.detailpengajuan', compact('pengajuan'));
    }

    public function terimapengajuan($id){
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Diterima';
        $pengajuan->save();
        return redirect()->route('tampilPengajuan')->with('success', 'Pengajuan berhasil diterima');
    }

    public function tolakpengajuan($id){
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'Ditolak';
        $pengajuan->save();
        return redirect()->route('tampilPengajuan')->with('success', 'Pengajuan berhasil ditolak');
    }
}
