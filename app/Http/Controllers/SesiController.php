<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\token;
use App\Models\event;
use App\Models\sop;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function token()
    {
        return view('user.index');
    }
    public function loginToken(Request $request)
    {
        $sop = sop::all();
        return view('user.index', compact('sop'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email Wajib Diisi',
            'password.required' => 'Password Wajib Diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'admin') {
                return redirect('index/admin');
            } elseif (Auth::user()->role == 'user') {
                return redirect('index/user');
            } elseif (Auth::user()->role == 'pengelola') { // Corrected elseif condition
                return redirect('index/admin');
            } 
        } else {
            return redirect('')->withErrors('Username dan Password Yang dimasukan tidak sesuai')->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/landing'); // atau halaman lain setelah logout
    }

    public function showCurrentDate()
    {
        $currentDate = Carbon::now()->format('M d, Y');
        return view('your.view', ['currentDate' => $currentDate]);
    }
}
