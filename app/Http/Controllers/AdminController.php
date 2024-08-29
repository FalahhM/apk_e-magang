<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    function index(){
        return view ('admin');
    }
    
    function kampus(){
        return view ('formkampus/kampus');
    }
}
