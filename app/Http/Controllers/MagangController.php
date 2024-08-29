<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    function nampil(){
        $userId = Auth::id();
        $user = User::find($userId);
        $contact_person = ContactPerson::where('user_id', $userId)->first();

        return view('formkampus/kampus', compact('user','contact_person'));
    }
}
