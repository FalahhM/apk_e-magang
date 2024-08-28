<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\Hash;

class SesiController extends Controller
{
    function index(){
        return view('login');
    }

    function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ],[
            'email.required'=>'Email wajib diisi',
            'password.required'=>'Password wajib diisi',
        ]);

        $infologin = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if(Auth::attempt($infologin)){
            if(Auth::user()->role == 'admin'){
                return redirect('/admin');
            } elseif(Auth::user()->role == 'kampus'){
                return redirect('admin/kampus');
            }
        } else {
            return redirect('')->withErrors('Email dan password yang dimasukkan tidak sesuai')->withInput();
        }
    }

    function formregister(){
        return view ('register');
    }

    function register(Request $request){
        $request->validate([
            // akun kampus
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'password' => 'required|string|confirmed|min:5',
            // contact person
            'namecp' => 'required|string|max:255',
            'emailcp' => 'required|string|email|max:255',
            'nohpcp' => 'required|string|max:15',
            'jabatancp' => 'required|string|max:255',
        ],[
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'email wajib diisi',
            'alamat.required' => 'alamat wajib diisi',
            'no_telp.required' => 'No.Telp wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // dd($request);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'password' =>Hash::make($request->password),
            'role' => 'kampus'
        ]);

        ContactPerson::create([
            'namecp' => $request->namecp,
            'emailcp' => $request->emailcp,
            'nohpcp' => $request->nohpcp,
            'jabatan' => $request->jabatancp,
            'user_id' => $user->id
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silahkan login.');
    }

    function logout(){
        Auth::logout();
        return redirect('');
    }
}
