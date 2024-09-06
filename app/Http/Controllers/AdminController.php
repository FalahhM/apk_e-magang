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
}
