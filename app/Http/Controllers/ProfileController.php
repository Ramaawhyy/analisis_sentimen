<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $userp = User::all();  // Fetch all profiles from the database
        return view('user.index', compact('users'));  // Pass the profiles to the view
    }
}