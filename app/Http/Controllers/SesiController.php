<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SesiController extends Controller
{
    function index(){
        return view('login');
    }

    function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi',
        'password.required' => 'Password wajib diisi',
    ]);

    $infologin = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    if (Auth::attempt($infologin)) {
        if (!Auth::user()->email_verified_at) {
            Auth::logout();
            return redirect('/')->withErrors('Silakan verifikasi email Anda sebelum login.');
        }

        if (Auth::user()->role == 'admin') {
            return redirect('/admin');
        } elseif (Auth::user()->role == 'kampus') {
            return redirect('/kampusdashboard');
        }
    } else {
        return redirect('/')->withErrors('Email dan password yang dimasukkan tidak sesuai')->withInput();
    }
}


    function formregister(){
        return view ('register');
    }


function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'alamat' => 'required|string|max:255',
        'no_telp' => 'required|string|max:15',
        'password' => 'required|string|confirmed|min:5',
        'namecp' => 'required|string|max:255',
        'emailcp' => 'required|string|email|max:255',
        'nohpcp' => 'required|string|max:15',
        'jabatancp' => 'required|string|max:255',
    ], [
        'name.required' => 'Nama wajib diisi',
        'email.required' => 'Email wajib diisi',
        'alamat.required' => 'Alamat wajib diisi',
        'no_telp.required' => 'No. Telp wajib diisi',
        'password.required' => 'Password wajib diisi',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'password' => Hash::make($request->password),
        'role' => 'kampus',
        'email_verified_at' => null,
    ]);

    ContactPerson::create([
        'namecp' => $request->namecp,
        'emailcp' => $request->emailcp,
        'nohpcp' => $request->nohpcp,
        'jabatan' => $request->jabatancp,
        'user_id' => $user->id
    ]);

    // Generate email verification token
    $user->email_verification_token = Str::random(32);
    $user->save();

    // Send verification email
    Mail::to($user->email)->send(new VerifyEmail($user));

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silahkan cek email Anda untuk verifikasi.');
}


function verifyEmail($id, $token)
{
    $user = User::find($id);

    if (!$user || $user->email_verification_token !== $token) {
        return redirect('/')->withErrors('Link verifikasi tidak valid.');
    }

    $user->email_verified_at = now();
    $user->email_verification_token = null;
    $user->save();

    return redirect('/')->with('success', 'Email Anda telah terverifikasi. Silahkan login.');
}



    function logout(){
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
    }
}
